<?php

namespace ZeframMvc;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\AbstractListenerAggregate;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'ZeframMvc\DispatchListener'     => 'ZeframMvc\Listener\DispatchListener',
                'ZeframMvc\RenderListener'       => 'ZeframMvc\Listener\RenderListener',
            ),
            'factories' => array(
                'ZeframMvc\Container'            => 'ZeframMvc\Service\ContainerFactory',
                'ZeframMvc\Bootstrap'            => 'ZeframMvc\Service\BootstrapFactory',
                'ZeframMvc\LegacyApplication'    => 'ZeframMvc\Service\LegacyApplicationFactory',
            ),
            'aliases' => array(
                'Bootstrap' => 'ZeframMvc\Bootstrap',
            ),
            'abstract_factories' => array(
                'ZeframMvc\Service\ResourceFactory',
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                'ZeframMvc\Controller' => 'ZeframMvc\Controller',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();

        $serviceManager = $application->getServiceManager();
        $events = $application->getEventManager();

        /** @var $dispatchListener AbstractListenerAggregate */
        $dispatchListener = $serviceManager->get('ZeframMvc\DispatchListener');
        $dispatchListener->attach($events);

        /** @var $renderListener AbstractListenerAggregate */
        $renderListener = $serviceManager->get('ZeframMvc\RenderListener');
        $renderListener->attach($events);
    }
}
