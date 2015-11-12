<?php

namespace ZeframMvc\ModuleManager\Listener;

use Traversable;
use Zend\ModuleManager\Listener\ConfigListener as Zf2ConfigListener;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\ModuleEvent;

/**
 * Config listener
 */
class ConfigListener extends Zf2ConfigListener
{
    protected function addConfig($key, $config)
    {
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        // ZF1 resources require lowercased identifiers. This is done here,
        // to avoid potential problems if different module configs use different
        // config keys for the same resource (i.e. FrontController,
        // frontController, or frontcontroller)
        if (is_array($config) && isset($config['resources']) {
            $config['resources'] = array_change_key_case((array) $config['resources']);
        }
        return parent::addConfig($key, $config);
    }
}
