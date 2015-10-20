<?php

namespace ZeframMvc\Loader;

use Zend\Loader\ModuleAutoloader as Zf2ModuleAutoloader;

class ModuleAutoloader extends Zf2ModuleAutoloader
{
    /**
     * {@inheritDoc}
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

        // If the module class was not found, check in directory that has
        // ZF1 module naming style, i.e. spinal-case instead of CamelCase.
        // Prepare module class path by replacing top-level namespace with
        // its spinal-cased version.
        $moduleNameParts = explode('\\', substr($class, 0, -7), 2);
        $moduleNameParts[0] = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1-$2', $moduleNameParts[0]));
        $moduleName = implode('\\', $moduleNameParts);

        $moduleClassPath = str_replace('\\', DIRECTORY_SEPARATOR, $moduleName);

        foreach ($this->paths as $path) {
            $path = $path . $moduleClassPath;

            if ($path == '.' || substr($path, 0, 2) == './' || substr($path, 0, 2) == '.\\') {
                $basePath = realpath('.');

                if (false === $basePath) {
                    $basePath = getcwd();
                }

                $path = rtrim($basePath, '\/\\') . substr($path, 1);
            }

            $classLoaded = $this->loadModuleFromDir($path, $class);
            if ($classLoaded) {
                return $classLoaded;
            }
        }

        return false;
    }
}

