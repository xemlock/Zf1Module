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

        // If top-level namespace is in spinal-case, convert it to CamelCase.
        // This probably is a module following ZF1 module naming convention.
        if (preg_match('/^[a-z][-.a-z]+/', $moduleName)) {
            $moduleNameParts = explode('\\', $moduleName, 2);
            $moduleNameParts[0] = $this->formatModuleName($moduleNameParts[0]);
            $moduleName = implode('\\', $moduleNameParts);
        }

        $class = $moduleName . '\Module';

        if (!class_exists($class)) {
            return false;
        }

        $module = new $class;
        return $module;
    }

    /**
     * Format a module name
     *
     * @param  string $name
     * @return string
     */
    public function formatModuleName($name)
    {
        // This code is taken from Zend_Application_Resource_Modules,
        // it transforms module directory name to module class prefix
        $name = strtolower($name);
        $name = str_replace(array('-', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }
}
