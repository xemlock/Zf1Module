Zf1Module
=========

Zf1Module allows ZF1 modules to work in Zend Framework 2 applications. It works by providing a custom `ModuleManager` that apart from handling ZF2 modules can deal with specifics of ZF1 modules.

## Installation

Add the following settings to your `application.config.php` and see the magic happens:

    'service_manager' => array(
        'factories' => array(
            'ModuleManager' => 'Zf1Module\Service\ModuleManagerFactory',
        ),
    )

## Configuration

Configuration for ZF1 application must be provided in config. Put resources configuration
under 'resources' key, resource plugin paths in 'pluginPaths'. These keys are case-insensitive.

Each module may provide a ZF2 compatible `Module.php` with Module class residing in module
namespace. In such case the module bootstrap file will be ignored, and the configuration provided
by the Module class will be merged with the general config.

For example you can setup ZF1 resources using ZF2 module configuration methods:

    public function getConfig()
    {
        return array(
            'resources' => array(
                'frontController' => array(
                    'controllerDirectory' => array(
                        'module-name' => __DIR__ . '/controllers',
                    ),
                ),
                'router' => array(
                    'routes' => require __DIR__ . '/configs/routes.config.php',
                ),
                'view' => array(
                    'helperPath' => array(
                        'ModuleName_View_Helper_' => __DIR__ . '/views/helpers',
                    ),
                    'scriptPath' => array(
                        __DIR__ . '/views/scripts',
                    ),
                ),
            ),
        );
    }

ZF1 modules that provide their own `Module.php` file must be listed along with the ZF2 modules
under 'modules' key in application config. Modules with `Bootstrap.php` file only may be listed
(not yet supported!) or be loaded using `modules` ZF1 resource.

ZF1 resources will be loaded by Bootstrap upon dispatch.

Currently an instance of `Zf1Module\Bootstrap\Bootstrap` class will be used as bootstrap.

The bootstrap instance will be provided with a container that extends a `Zend_Registry` but is a wrapper around `ServiceManager`. Services saved via this registry have prefixed names, to distinguish them from ZF2 services. 

You can provide custom container class by overwriting `Zf1Module\Container` service config key.

Bootstrap is registered in `ServiceManager` at `Bootstrap` key.

The bootstrap will be initialized with an instance of `Zf1Module\LegacyApplication` instance that
contain reference to `ServiceManager` instance. To access ZF2 services in ZF1 modules one can use:

    Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getServiceManager();





