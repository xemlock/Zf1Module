<?php

namespace ZeframMvc;

use Zend\Console\Console;
use Zend\Mvc\Application as Zf2Application;
use Zend\Mvc\MvcEvent;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'ZeframMvc\DispatchListener'     => 'ZeframMvc\DispatchListener',
                'ZeframMvc\RouteListener'        => 'ZeframMvc\RouteListener',
                'ZeframMvc\SendResponseListener' => 'ZeframMvc\SendResponseListener',
                'ZeframMvc\Request'              => 'Zend_Controller_Request_Http',
                'ZeframMvc\Response'             => 'Zend_Controller_Response_Http',
            ),
            'factories' => array(
                'resource.FrontController'       => 'ZeframMvc\Service\FrontControllerFactory',
                'ZeframMvc\Container'            => 'ZeframMvc\Service\ContainerFactory',
                'ZeframMvc\Router'               => 'ZeframMvc\Service\RouterFactory',
                'ZeframMvc\Bootstrap'            => 'ZeframMvc\Service\BootstrapFactory',
                'ZeframMvc\LegacyApplication'    => 'ZeframMvc\Service\LegacyApplicationFactory',
            ),
            'aliases' => array(
                'Bootstrap'         => 'ZeframMvc\Bootstrap',
                'LegacyApplication' => 'ZeframMvc\LegacyApplication',
            ),
            'abstract_factories' => array(
                'ZeframMvc\Service\ResourceFactory',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getApplication()->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 1000);
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 1000);
        $events->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), 1000);
    }

    public function onDispatchError(MvcEvent $e)
    {
        if (Console::isConsole() || $e->getError() !== Zf2Application::ERROR_ROUTER_NO_MATCH) {
            return;
        }
        $e->setParam('dispatch-zf1', true);
        // TODO should event propagation be stopped?
    }

    public function onRender(MvcEvent $e)
    {
        if (!$e->getParam('dispatch-zf1')) {
            return;
        }
        // disable ZF2 rendering if in ZF1 context
        $e->stopPropagation(true);
    }

    public function onFinish(MvcEvent $e)
    {
        if (!$e->getParam('dispatch-zf1')) {
            return;
        }

        $application = $e->getApplication();

        /** @var $bootstrap \Zend_Application_Bootstrap_Bootstrapper */
        $bootstrap = $application->getServiceManager()->get('ZeframMvc\Bootstrap');
        $bootstrap->bootstrap();

        /** @var $response \Zend_Controller_Response_Abstract */
        $response = $bootstrap->run();
        if ($response) {
            $response->sendResponse();
        }

        $e->stopPropagation(true);
    }
}
