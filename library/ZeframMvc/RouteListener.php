<?php

namespace ZeframMvc;

use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\RouteListener as Zf2RouteListener;
use Zend\Mvc\Router\RouteMatch;
use Zend\Stdlib\ResponseInterface;

class RouteListener extends Zf2RouteListener
{
    public function attach(EventManagerInterface $events)
    {
        // run this listener before built-in RouteListener
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), 1000);
    }

    public function onRoute($e)
    {
        $target     = $e->getTarget();
        $request    = $e->getRequest();
        $router     = $e->getRouter();
        $routeMatch = $router->match($request);

        if (!$routeMatch instanceof RouteMatch) {
            $results = $target->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ZF1, $e, function ($result) {
                if ($result instanceof ResponseInterface) {
                    return true;
                }
                return false;
            });
            $return = $results->last();
            if ($return instanceof ResponseInterface) {
                return $return;
            }

            $e->setError(Application::ERROR_ROUTER_NO_MATCH);

            $results = $target->getEventManager()->trigger(MvcEvent::EVENT_DISPATCH_ERROR, $e);
            if (count($results)) {
                $return  = $results->last();
            } else {
                $return = $e->getParams();
            }
            return $return;
        }

        $e->setRouteMatch($routeMatch);
        return $routeMatch;
    }
}
