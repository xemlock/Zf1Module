<?php

namespace ZeframMvc\Service;

use Zend\Mvc\Service\ServiceListenerFactory as ZendServiceListenerFactory;

/**
 * @package    ZeframMvc
 * @subpackage Service
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
            'Dispatcher'            => 'ZeframMvc\Dispatcher',
            'DispatchListener'      => 'ZeframMvc\DispatchListener',
            'SendResponseListener'  => 'ZeframMvc\SendResponseListener',
            'Request'               => 'Zend_Controller_Request_Http',
            'Response'              => 'Zend_Controller_Response_Http'
        ),
        'factories' => array(
            'Application'           => 'ZeframMvc\Service\ApplicationFactory',
            'Bootstrap'             => 'ZeframMvc\Service\BootstrapFactory',
            'Config'                => 'Zend\Mvc\Service\ConfigFactory',
            'DependencyInjector'    => 'Zend\Mvc\Service\DiFactory',
            'FrontController'       => 'ZeframMvc\Service\FrontControllerFactory',
            'Router'                => 'ZeframMvc\Service\RouterFactory',
        ),
        'aliases' => array(
            'Configuration'             => 'Config',
            'Di'                        => 'DependencyInjector',
            'Zend\Di\LocatorInterface'  => 'DependencyInjector',
        ),
        'abstract_factories' => array(
            'ZeframMvc\Service\ResourceFactory',
        ),
  );
}
