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
        try {
            $pluginClass = $bootstrap->getPluginLoader()->load(strtolower($requestedName));
        } catch (\Zend_Loader_Exception $e) {
            $pluginClass = null;
        }
        return (bool) $pluginClass;
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $options = isset($config[$requestedName]) ? $config[$requestedName] : array();

        /** @var $bootstrap \Zend_Application_Bootstrap_ResourceBootstrapper */
        $bootstrap = $serviceLocator->get('Bootstrap');
        if (!$bootstrap->hasPluginResource($requestedName)) {
            $bootstrap->registerPluginResource($requestedName);
        }

        return $bootstrap->getPluginResource($requestedName)->setOptions($options)->init();
    }
}
