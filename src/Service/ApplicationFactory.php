<?php

namespace Zf1Module\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zf1Module\Application\Resource\ServiceLocator as ServiceLocatorResource;
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
        $applicationOptions = new ApplicationOptions();

        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        if (isset($config['zf1'])) {
            $applicationOptions->setFromArray($config['zf1']);
        }

        $applicationClass = $applicationOptions->getApplicationClass();

        /** @var $application \Zend_Application */
        $application = new $applicationClass(
            $applicationOptions->getEnvironment(),
            $applicationOptions->getConfig(),
            $applicationOptions->getSuppressNotFoundWarnings()
        );

        $bootstrap = $application->getBootstrap();
        if (!$bootstrap->hasPluginResource('ServiceLocator')) {
            $bootstrap->registerPluginResource(new ServiceLocatorResource($serviceLocator));
        }

        return $application;
    }
}
