<?php

namespace ZeframMvc\Service;

use ZeframMvc\Bootstrap\Bootstrap;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BootstrapFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \ZeframMvc\Bootstrap\Bootstrap;
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = isset($config['bootstrap']) ? $config['bootstrap'] : array();

        return new Bootstrap($serviceLocator, $options);
    }
}
