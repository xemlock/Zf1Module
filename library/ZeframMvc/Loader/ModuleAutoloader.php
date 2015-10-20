<?php

namespace ZeframMvc\Loader;

use Zend\Loader\ModuleAutoloader as Zf2ModuleAutoloader;

/**
 * This module autoloader once instantiated can be accessed externally
 * and can handle spinal-case namespaces.
 */
class ModuleAutoloader extends Zf2ModuleAutoloader
{
    /**
     * Instance so that we can hook up to load module classes residing in spinal-case namespaces (ZF1)
     * In PHP 5.4.24-5.4.45, 5.5.8+ autoloading is not performed for invalid namespaces, in
     * 5.1.0-5.4.23 and 5.5.0-5.5.7 it is. To properly handle spinal-case namespaces we must
     * be able to explicitly call autoload() from the outside - hence the getInstance() method.
     *
     * @var \ZeframMvc\Loader\ModuleAutoloader
     */
    protected static $_instance;

    /**
     * {@inheritDoc}
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        self::$_instance = $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadModuleFromDir($dirPath, $class)
    {
        // Module class may reside in a directory that uses spinal-case
        // naming style (convention used in ZF1). In order to handle such
        // case convert spinal-case (invalid) namespace to CamelCase and try to load
        // module class using this namespace - dir path remains intact.
        if (preg_match('/^[a-z][-.a-z]+\\\\/', $class)) {
            list($moduleName, $moduleClass) = explode('\\', $class, 2);
            $moduleName = self::formatModuleName($moduleName);
            $class = $moduleName . '\\' . $moduleClass;
        }
        return parent::loadModuleFromDir($dirPath, $class);
    }


    /**
     * Format a module name
     *
     * @param  string $name
     * @return string
     */
    public static function formatModuleName($name)
    {
        $name = strtolower($name);
        $name = str_replace(array('-', '.'), ' ', $name);
        $name = ucwords($name);
        $name = str_replace(' ', '', $name);
        return $name;
    }

    /**
     * Get existing instance of module autoloader
     *
     * @return ModuleAutoloader|null
     */
    public static function getInstance()
    {
        return self::$_instance;
    }
}

