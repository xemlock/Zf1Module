<?php

namespace ZeframMvc;

use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();

        $serviceManager = $application->getServiceManager();

        $events = $application->getEventManager();
        $events->attach($serviceManager->get('ZeframMvc\DispatchListener'));
        $events->attach($serviceManager->get('ZeframMvc\SendResponseListener'));
    }

    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'ZeframMvc\DispatchListener'     => 'ZeframMvc\DispatchListener',
                'ZeframMvc\SendResponseListener' => 'ZeframMvc\SendResponseListener',
                'ZeframMvc\Request'              => 'Zend_Controller_Request_Http',
                'ZeframMvc\Response'             => 'Zend_Controller_Response_Http'
            ),
            'factories' => array(
                'Application'                    => 'ZeframMvc\Service\ApplicationFactory',
                'resource.FrontController'       => 'ZeframMvc\Service\FrontControllerFactory',
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
}