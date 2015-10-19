<?php

namespace ZeframMvc\Loader;

use Zend\Loader\ModuleAutoloader as Zf2ModuleAutoloader;

class ModuleAutoloader extends Zf2ModuleAutoloader
{
    /**
     * Autoload a module class
     *
     * @param   $class
     * @return  mixed
     *          False [if unable to load $class]
     *          get_class($class) [if $class is successfully loaded]
     */
    public function autoload($class)
    {
        if (false !== ($classLoaded = parent::autoload($class))) {
            return $classLoaded;
        }

        // Limit scope of this autoloader
        if (substr($class, -7) !== '\Module') {
            return false;
        }

        $moduleName = substr($class, 0, -7);

        // Module class may reside in a directory that uses spinal-case
        // naming style (convention used in ZF1). In order to handle such
        // case convert CamelCase namespace to spinal-case and try to load
        // module class using this (invalid) namespace. If file matching
        // the namespace is found, it will be imported.

        $namespace = substr($moduleName, 0, $pos = strpos($moduleName, '\\'));
        $namespace = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1-$2', $namespace));

        $class = $namespace . substr($moduleName, $pos) . '\Module';

        return parent::autoload($class);
    }
}

