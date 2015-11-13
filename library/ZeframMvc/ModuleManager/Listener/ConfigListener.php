<?php

namespace ZeframMvc\ModuleManager\Listener;

use Traversable;
use Zend\ModuleManager\Listener\ConfigListener as Zf2ConfigListener;
use Zend\Stdlib\ArrayUtils;

/**
 * Config listener
 *
 * ZF1 resources require their config keys in lowercase. This listener ensures
 * that keys under 'resources' array are lowercased before being stored. This
 * is done here for performance reasons, and to avoid potential problems if
 * different module configs use differently cased config keys for the same
 * resource, i.e. FrontController, frontController, or frontcontroller.
 */
class ConfigListener extends Zf2ConfigListener
{
    protected function addConfig($key, $config)
    {
        if ($config instanceof Traversable) {
            $config = ArrayUtils::iteratorToArray($config);
        }
        if (is_array($config) && isset($config['resources'])) {
            $config['resources'] = array_change_key_case((array) $config['resources'], CASE_LOWER);
        }
        return parent::addConfig($key, $config);
    }
}
