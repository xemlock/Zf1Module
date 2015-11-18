<?php

namespace ZeframMvc\Service;

use ZeframMvc\LegacyApplication;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LegacyApplicationFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return LegacyApplication
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LegacyApplication($serviceLocator);
    }
}
