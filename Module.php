<?php

namespace Zf1Module;

use Zend\Mvc\MvcEvent;

class Module
{
    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
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
