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
            'DispatchListener'     => 'Zend\Mvc\DispatchListener',
            'RouteListener'        => 'Zend\Mvc\RouteListener',
            'Router'               => 'ZeframMvc\Router\EmptyRouteStack',
            'SendResponseListener' => 'Zend\Mvc\SendResponseListener',
        ),
        'factories' => array(
            'Application'        => 'ZeframMvc\Service\ApplicationFactory',
            'Config'             => 'Zend\Mvc\Service\ConfigFactory',
            'DependencyInjector' => 'Zend\Mvc\Service\DiFactory',
            'Request'            => 'Zend\Mvc\Service\RequestFactory',
            'Response'           => 'Zend\Mvc\Service\ResponseFactory',
        ),
        'aliases' => array(
            'Configuration'            => 'Config',
            'Di'                       => 'DependencyInjector',
            'Zend\Di\LocatorInterface' => 'DependencyInjector',
        ),
    );
}
