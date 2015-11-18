<?php

namespace Zf1Module\Service;

use Zf1Module\Application;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Application
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Application($serviceLocator);
    }
}
