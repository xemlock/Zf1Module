<?php

namespace ZeframMvc\Service;

use ZeframMvc\Bootstrap\Bootstrap;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\ArrayUtils;

class BootstrapFactory implements FactoryInterface
{
    /**
     * Create the Bootstrap service
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \ZeframMvc\Bootstrap\Bootstrap;
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();
        $bootstrapConfig = isset($config['bootstrap']) ? $config['bootstrap'] : array();

        if (isset($config['resources'])) {
            $resourceConfig = (array) $config['resources'];
            foreach ($resourceConfig as $key => $value) {
                if (is_string($key) && $key !== ($resourceKey = strtolower($key))) {
                    $resourceConfig[$resourceKey] = ArrayUtils::merge(
                        $resourceConfig[$resourceKey],
                        $value
                    );
                }
            }
        } else {
            $resourceConfig = array();
        }
        $bootstrapConfig['resources'] = $resourceConfig;

        return new Bootstrap($serviceLocator, $bootstrapConfig);
    }
}
