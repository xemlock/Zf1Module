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
        /** @var $bootstrap \Zend_Application_Bootstrap_ResourceBootstrapper */
        $bootstrap = $serviceLocator->get('Bootstrap');

        if (!$bootstrap->hasPluginResource($requestedName)) {
            $bootstrap->registerPluginResource($requestedName);
        }

        /** @var $pluginResource \Zend_Application_Resource_ResourceAbstract */
        $pluginResource = $bootstrap->getPluginResource($requestedName);

        // Setup and initialize resource
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();

        $configKey = $this->getConfigKey($requestedName);
        $options = isset($config[$configKey]) ? $config[$configKey] : array();

        $service = $pluginResource->setOptions($options)->init();

        // Prevent 'The factory was called but did not return an instance'
        // exception, since resource's init() method may not return anything,
        // which is equivalent to returning NULL. Such is the case with
        // Zend_Application_Resource_Session::init().
        if ($service === null) {
            $service = true;
        }

        return $service;
    }

    /**
     * Gets service config key
     *
     * @param string $serviceName
     * @return string
     */
    public function getConfigKey($serviceName)
    {
        // Create service config key by transforming the name from CamelCase
        // to snake_case, i.e. config key for 'CacheManager' is 'cache_manager',
        // for 'MultiDb' (or 'MultiDB') is 'multi_db'

        // To properly handle sequences of consecutive uppercase characters,
        // insert underscore before each word (except the first one) starting with
        // an uppercased letter and followed by one or more lowercased letters.
        $configKey = preg_replace('/(?<!^)([A-Z])(?=[a-z])/', '_$1', $serviceName);

        // Insert an underscore before a possible all uppercase suffix that wasn't
        // matched in the previous step.
        $configKey = strtolower(preg_replace('/(?<=[a-z])([A-Z])/', '_$1', $configKey));

        return $configKey;
    }
}
