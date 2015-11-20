<?php

namespace Zf1Module\Service;

use Zend_Application_Bootstrap_Bootstrap as Bootstrap;
use Zf1Module\Options\ApplicationOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BootstrapFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * Bootstrap service is bootstrapped prior to returning
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Bootstrap
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $application \Zend_Application */
        $application = $serviceLocator->get('Zf1Module\Application');

        $bootstrap = $application->getBootstrap();
        $bootstrap->bootstrap();

        return $bootstrap;
    }
}
