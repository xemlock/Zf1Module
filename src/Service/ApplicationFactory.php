<?php

namespace Zf1Module\Service;

use Zf1Module\Application;
use Zf1Module\Options\ApplicationOptions;
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
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = new ApplicationOptions($config);

        $application = new Application($serviceLocator, $options->getEnvironment(), array_merge(
            $options->toArray(),
            array('container' => $serviceLocator->get('Zf1Module\Container'))
        ));

        return $application;
    }
}
