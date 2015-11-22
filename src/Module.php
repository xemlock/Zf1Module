<?php

namespace Zf1Module;

use Zend\Mvc\MvcEvent;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'Zf1Module\DispatchListener' => 'Zf1Module\Listener\DispatchListener',
                'Zf1Module\RenderListener'   => 'Zf1Module\Listener\RenderListener',
            ),
            'factories' => array(
                'Zf1Module\Container'   => 'Zf1Module\Service\ContainerFactory',
                'Zf1Module\Bootstrap'   => 'Zf1Module\Service\BootstrapFactory',
                'Zf1Module\Application' => 'Zf1Module\Service\ApplicationFactory',
            ),
            'aliases' => array(
                'Bootstrap' => 'Zf1Module\Bootstrap',
            ),
        );
    }

    public function getControllerConfig()
    {
        return array(
            'invokables' => array(
                'Zf1Module\Controller' => 'Zf1Module\Controller',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $application = $e->getApplication();

        $serviceManager = $application->getServiceManager();
        $events = $application->getEventManager();

        $serviceManager->get('Zf1Module\DispatchListener')->attach($events);
        $serviceManager->get('Zf1Module\RenderListener')->attach($events);
    }
}
