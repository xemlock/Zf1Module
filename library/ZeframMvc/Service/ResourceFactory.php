<?php

namespace ZeframMvc\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResourceFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var $bootstrap \Zend_Application_Bootstrap_ResourceBootstrapper */
        $bootstrap = $serviceLocator->get('Bootstrap');

        // If a plugin resource is already registered in bootstrap assume
        // it can be (or has already been) instantiated.
        if ($bootstrap->hasPluginResource($requestedName)) {
            return true;
        }

        // Try to register plugin resource of requested name
        try {
            $bootstrap->registerPluginResource($requestedName);
        } catch (\Exception $e) {
            // Unable to register plugin resource, fall
            return false;
        }

        // If plugin resource regustration succeeds, try to instantiate
        // the plugin resource
        try {
            $pluginResourceLoaded = (bool) $bootstrap->getPluginResource($requestedName);
        } catch (\Exception $e) {
            $pluginResourceLoaded = false;
        }

        $bootstrap->unregisterPluginResource($requestedName);

        return $pluginResourceLoaded;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        // normalize service name by transforming it to snake_case
        $normalizedName = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1_$2', $requestedName));

        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = isset($config[$normalizedName]) ? $config[$normalizedName] : array();

        /** @var $bootstrap \Zend_Application_Bootstrap_ResourceBootstrapper */
        $bootstrap = $serviceLocator->get('Bootstrap');
        if (!$bootstrap->hasPluginResource($requestedName)) {
            $bootstrap->registerPluginResource($requestedName);
        }

        /** @var $pluginResource \Zend_Application_Resource_ResourceAbstract */
        $pluginResource = $bootstrap->getPluginResource($requestedName);

        return $pluginResource->setOptions($options)->init();
    }
}
