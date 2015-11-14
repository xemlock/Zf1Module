<?php

namespace ZeframMvc\Service;

use Zend\Mvc\Service\ServiceListenerFactory as ZendServiceListenerFactory;

/**
 * @package    ZeframMvc
 * @subpackage Service
 *
 * Use this for minimal setup, to run only ZF1 modules
 */
class ServiceListenerFactory extends ZendServiceListenerFactory
{
    /**
     * Default mvc-related service configuration -- can be overridden by modules.
     *
     * @var array
     */
    protected $defaultServiceConfig = array(
        'invokables' => array(
            'ZeframMvc\DispatchListener'     => 'ZeframMvc\DispatchListener',
            'ZeframMvc\SendResponseListener' => 'ZeframMvc\SendResponseListener',
            'ZeframMvc\Request'              => 'Zend_Controller_Request_Http',
            'ZeframMvc\Response'             => 'Zend_Controller_Response_Http'
        ),
        'factories' => array(
            'Application'                 => 'ZeframMvc\Service\ApplicationFactory',
            'Config'                      => 'Zend\Mvc\Service\ConfigFactory',
            'DependencyInjector'          => 'Zend\Mvc\Service\DiFactory',
            'Router'                      => 'Zend\Mvc\Service\RouterFactory',
            'resource.FrontController'    => 'ZeframMvc\Service\FrontControllerFactory',
            'ZeframMvc\Router'            => 'ZeframMvc\Service\RouterFactory',
            'ZeframMvc\Bootstrap'         => 'ZeframMvc\Service\BootstrapFactory',
            'ZeframMvc\LegacyApplication' => 'ZeframMvc\Service\LegacyApplicationFactory',
        ),
        'aliases' => array(
            'Configuration'            => 'Config',
            'Di'                       => 'DependencyInjector',
            'Zend\Di\LocatorInterface' => 'DependencyInjector',
            'Bootstrap'                => 'ZeframMvc\Bootstrap',
            'LegacyApplication'        => 'ZeframMvc\LegacyApplication',
        ),
        'abstract_factories' => array(
            'ZeframMvc\Service\ResourceFactory',
        ),
  );
}
