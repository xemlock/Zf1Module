<?php

namespace ZeframMvc\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * @deprecated Use default ZF2 Router instead, ZF1 router will be handled by ResourceFactory
 */
class RouterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();

        $routerConfig = isset($config['resources']['router']) ? $config['resources']['router'] : array();
        $routerClass = isset($routerConfig['class']) ? $routerConfig['class'] : 'Zend_Controller_Router_Rewrite';

        /** @var $router \Zend_Controller_Router_Interface */
        $router = new $routerClass();

        if (isset($routerConfig['chainNameSeparator'])) {
            $router->setChainNameSeparator($routerConfig['chainNameSeparator']);
        }

        if (isset($routerConfig['useRequestParametersAsGlobal'])) {
            $router->useRequestParametersAsGlobal($routerConfig['useRequestParametersAsGlobal']);
        }

        if (isset($routerConfig['routes'])) {
            $router->addConfig(new \Zend_Config($routerConfig['routes']));
        }

        return $router;
    }
}
