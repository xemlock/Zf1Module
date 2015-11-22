Zf1Module
=========

Zf1Module allows ZF1 modules to work in Zend Framework 2 applications. It works by providing a custom `ModuleManager` that apart from handling ZF2 modules can deal with specifics of ZF1 modules.

## Installation

Add `Zf1Module` to the modules list in the `application.config.php` and see the magic happens. Almost, because you need to configure it first. 

## Configuration

Configuration for ZF1 application must be provided in `Config` service. Put resources configuration
under 'resources' key, resource plugin paths in 'pluginPaths', bootstrap path/class configuration under 'bootstrap' key. These keys are case-insensitive.

For example you can setup resources using ZF2 module configuration methods:

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
				'modules' => true, // load ZF1 modules
            ),
        );
    }

ZF1 modules will be loaded by the `modules` ZF1 resource.

ZF1 resources will be loaded by Bootstrap upon retrieval from service manager using `Zf1Module\Bootstrap` key.

Bootstrap class may be specified the same way as in ZF1, using a `bootstrap` key in config.

The bootstrap will be instantiated by an instance of `Zf1Module\Application` that contains reference to `ServiceManager` instance. To access ZF2 services in ZF1 modules one can use:

    Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getServiceManager();

Bootstrap retrieved by `Zf1Module\Bootstrap` key is bootstrapped. To retrieve bootstrap instance without bootstrapping its resources, use:

	$serviceManager->get('Zf1Module\Application')->getBootstrap(); 




