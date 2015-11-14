<?php

namespace ZeframMvc\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend_Controller_Front;
use Zend_Controller_Action_HelperBroker;

class FrontControllerFactory implements FactoryInterface
{
    /**
     * Create front controller service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Zend_Controller_Front
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->has('Config') ? $serviceLocator->get('Config') : array();

        $options = isset($config['resources']['frontcontroller']) ? $config['resources']['frontcontroller'] : array();
        $class = isset($options['class']) ? $options['class'] : 'Zend_Controller_Front';

        /** @var $frontController Zend_Controller_Front */
        $frontController = call_user_func(array($class, 'getInstance'));

        // $frontController->setDispatcher($serviceLocator->get('Dispatcher'));
        $frontController->setRouter($serviceLocator->get('ZeframMvc\Router')); // initialize router, otherwise it will not be initialized
        $frontController->setRequest($serviceLocator->get('ZeframMvc\Request'));
        $frontController->setResponse($serviceLocator->get('ZeframMvc\Response'));

        // Set 'bootstrap' param so that retrieving resources from bootstrap
        // via front controller still works
        $frontController->setParam('bootstrap', $serviceLocator->get('Bootstrap'));

        // Setup front controller options
        $this->init($frontController, $options);

        return $frontController;
    }

    /**
     * Initialize Front Controller
     *
     * @param Zend_Controller_Front $front
     * @param array $options
     * @return void
     */
    protected function init(Zend_Controller_Front $front, array $options)
    {
        // Zend_Application_Resource_Frontcontroller has the following issues:
        // - dispatcher cannot be provided as an already initialized object
        // - dispatcher is not set before setting default module, default
        //   controller, default action, etc.action,  which can result in
        //   incoherent stat
        // - request must be set before setting baseUrl to front controller,
        //   otherwise it will be overwritten by next request
        // The code below is copied from Zend_Application_Resource_Frontcontroller::init().
        // The branch for setting the dispatcher was removed due to reasons
        // listed above, and to the fact, that dispatcher is expected to be
        // already set up.

        foreach ($options as $key => $value) {
            switch (strtolower($key)) {
                case 'controllerdirectory':
                    if (is_string($value)) {
                        $front->setControllerDirectory($value);
                    } elseif (is_array($value)) {
                        foreach ($value as $module => $directory) {
                            $front->addControllerDirectory($directory, $module);
                        }
                    }
                    break;

                case 'modulecontrollerdirectoryname':
                    $front->setModuleControllerDirectoryName($value);
                    break;

                case 'moduledirectory':
                    if (is_string($value)) {
                        $front->addModuleDirectory($value);
                    } elseif (is_array($value)) {
                        foreach ($value as $moduleDir) {
                            $front->addModuleDirectory($moduleDir);
                        }
                    }
                    break;

                case 'defaultcontrollername':
                    $front->setDefaultControllerName($value);
                    break;

                case 'defaultaction':
                    $front->setDefaultAction($value);
                    break;

                case 'defaultmodule':
                    $front->setDefaultModule($value);
                    break;

                case 'baseurl':
                    if (!empty($value)) {
                        $front->setBaseUrl($value);
                    }
                    break;

                case 'params':
                    $front->setParams($value);
                    break;

                case 'plugins':
                    foreach ((array) $value as $pluginClass) {
                        $stackIndex = null;
                        if (is_array($pluginClass)) {
                            $pluginClass = array_change_key_case($pluginClass, CASE_LOWER);
                            if (isset($pluginClass['class'])) {
                                if (isset($pluginClass['stackindex'])) {
                                    $stackIndex = $pluginClass['stackindex'];
                                }

                                $pluginClass = $pluginClass['class'];
                            }
                        }

                        $plugin = new $pluginClass();
                        $front->registerPlugin($plugin, $stackIndex);
                    }
                    break;

                case 'returnresponse':
                    $front->returnResponse((bool) $value);
                    break;

                case 'throwexceptions':
                    $front->throwExceptions((bool) $value);
                    break;

                case 'actionhelperpaths':
                    if (is_array($value)) {
                        foreach ($value as $helperPrefix => $helperPath) {
                            Zend_Controller_Action_HelperBroker::addPath($helperPath, $helperPrefix);
                        }
                    }
                    break;

                default:
                    $front->setParam($key, $value);
                    break;
            }
        }
    }
}

