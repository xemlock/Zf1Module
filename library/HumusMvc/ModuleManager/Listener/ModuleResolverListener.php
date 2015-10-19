<?php

namespace HumusMvc\ModuleManager\Listener;

use Zend\ModuleManager\Listener\AbstractListener;
use Zend\ModuleManager\ModuleEvent;

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

        if (!class_exists($class)) {
            // If expected module class was not found, check for the possible class name variations:
            // - If module directory uses ZF1 naming style (spinal-case instead of CamelCase), check
            //   for Module class in Camel-Cased module name namespace
            // - If that fails, check for class name prefixed with Camel-Cased module name
            //   (no autoloading involved here)

            $moduleName = $this->formatModuleName($moduleName);
            $class      = $moduleName . '\Module';

            if (!class_exists($class)) {
                $class = $moduleName . '_Module';
                if (!class_exists($class, false)) {
                    return false;
                }
            }
        }

        $module = new $class;
        return $module;
    }

    /**
     * Format a module name to the module class prefix
     *
     * @param  string $name
     * @return string
     */
    protected function formatModuleName($name)
    {
        $name = strtolower($name);
        $name = str_replace(array('-', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }
}
