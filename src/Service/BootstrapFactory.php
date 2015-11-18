<?php

namespace Zf1Module\Service;

use Zf1Module\Bootstrap\Bootstrap;
use Zf1Module\Options\BootstrapOptions;
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

        $bootstrap = new Bootstrap($serviceLocator->get('Zf1Module\Application'));
        $bootstrap->setContainer($serviceLocator->get('Zf1Module\Container'));
        $bootstrap->setOptions($options->toArray());

        return $bootstrap;
    }
}
