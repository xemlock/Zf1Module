<?php

namespace Zf1Module\Service;

use Zf1Module\Container;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ContainerFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Container
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new Container($serviceLocator);
    }
}
