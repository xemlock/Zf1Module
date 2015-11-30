<?php

namespace Zf1Module\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf1Module\Options\ApplicationOptions;

class ApplicationFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \Zend_Application
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = new ApplicationOptions(isset($config['zf1']) ? $config['zf1'] : array());

        $applicationClass = $options->getApplicationClass();

        /** @var $application \Zend_Application */
        $application = new $applicationClass(
            $options->getEnvironment(),
            $options->getConfig(),
            $options->getSuppressNotFoundWarnings()
        );

        return $application;
    }
}
