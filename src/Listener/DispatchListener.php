<?php

namespace Zf1Module\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Application as Zf2Application;

class DispatchListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 1000);
    }

    public function onDispatchError(MvcEvent $e)
    {
        if ($e->getError() !== Zf2Application::ERROR_ROUTER_NO_MATCH) {
            return;
        }

        $routeMatch = new RouteMatch(array());
        $routeMatch->setParam('controller', 'Zf1Module\DispatchController');

        $e->setError(null);
        $e->setRouteMatch($routeMatch);

        return $routeMatch;
    }
}
