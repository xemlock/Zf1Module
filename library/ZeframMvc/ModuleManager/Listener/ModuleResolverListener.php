<?php

namespace ZeframMvc\ModuleManager\Listener;

use Zend\ModuleManager\Listener\AbstractListener;
use Zend\ModuleManager\ModuleEvent;
use ZeframMvc\Loader\ModuleAutoloader;

class ModuleResolverListener extends AbstractListener
{
    /**
     * @param  ModuleEvent $e
     * @return object|false False if module class does not exist
     */
    public function __invoke(ModuleEvent $e)
    {
        $moduleName = $e->getModuleName();
        $class      = $moduleName . '\Module';

        // autoloading will not be triggered for invalid namespaces (PHP 5.6)
        // we need to call autoload explicitly
        if (!class_exists($class)) {
            $moduleAutoloader = ModuleAutoloader::getInstance();
            if ($moduleAutoloader && ($loadedClass = $moduleAutoloader->autoload($class))) {
                $class = $loadedClass;
            } else {
                return false;
            }
        }

        $module = new $class;
        return $module;
    }
}
