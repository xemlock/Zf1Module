<?php

namespace Zf1Module\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf1Module\Application;
use Zf1Module\Options\ApplicationOptions;

class ApplicationFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Zf1Module\Application
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = new ApplicationOptions(isset($config['zf1']) ? $config['zf1'] : array());

        $application = new Application(
            $options->getEnvironment(),
            $options->getConfig(),
            $options->getSuppressNotFoundWarnings()
        );

        return $application;
    }
}
