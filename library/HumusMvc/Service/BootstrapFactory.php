<?php

namespace HumusMvc\Service;

use HumusMvc\Bootstrap\Bootstrap;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BootstrapFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \HumusMvc\Bootstrap\Bootstrap;
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Bootstrap($serviceLocator, $serviceLocator->get('ApplicationConfig'));
    }
}
