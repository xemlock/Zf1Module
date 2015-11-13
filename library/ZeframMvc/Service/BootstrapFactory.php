<?php

namespace ZeframMvc\Service;

use ZeframMvc\Bootstrap\Bootstrap;
use ZeframMvc\Options\BootstrapOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BootstrapFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return Bootstrap
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = new BootstrapOptions($config);

        $bootstrap = new Bootstrap($serviceLocator->get('LegacyApplication'));
        $bootstrap->setOptions($options->toArray());
        return $bootstrap;
    }
}
