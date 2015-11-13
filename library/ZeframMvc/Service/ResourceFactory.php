<?php

namespace ZeframMvc\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResourceFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        if (substr($requestedName, 0, 9) !== 'resource.') {
            return false;
        }
        $resourceName = substr($requestedName, 9);

        /** @var $bootstrap \Zend_Application_Bootstrap_ResourceBootstrapper */
        $bootstrap = $serviceLocator->get('Bootstrap');

        return $bootstrap->hasPluginResource($resourceName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $resourceName = substr($requestedName, 9);

        /** @var $bootstrap \Zend_Application_Bootstrap_ResourceBootstrapper */
        $bootstrap = $serviceLocator->get('Bootstrap');

        /** @var $pluginResource \Zend_Application_Resource_ResourceAbstract */
        $pluginResource = $bootstrap->getPluginResource($resourceName);
        $service = $pluginResource->init();

        // Prevent 'The factory was called but did not return an instance'
        // exception, since resource's init() method may not return anything,
        // which is equivalent to returning NULL. Such is the case with
        // Zend_Application_Resource_Session::init().
        if ($service === null) {
            $service = true;
        }

        return $service;
    }

}
