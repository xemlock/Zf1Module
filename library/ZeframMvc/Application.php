<?php

namespace ZeframMvc;

use Zend\Stdlib\ResponseInterface;

/**
 * Main application class for invoking applications
 *
 * Expects the user will provide a configured ServiceManager, configured with
 * the following services:
 *
 * - EventManager
 * - ModuleManager
 * - Request
 * - Response
 * - Router
 * - DispatchListener
 * - SendResponseListener
 *
 * The most common workflow is:
 * <code>
 * $services = new Zend\ServiceManager\ServiceManager($servicesConfig);
 * $app      = new Application($appConfig, $services);
 * $app->bootstrap();
 * $app->run();
 * </code>
 *
 * bootstrap() opts in to the default route, dispatch, and view listeners,
 * sets up the MvcEvent, and triggers the bootstrap event. This can be omitted
 * if you wish to setup your own listeners and/or workflow; alternately, you
 * can simply extend the class to override such behavior.
 *
 * @package    ZeframMvc
 */
class Application extends \Zend\Mvc\Application
{
    /**
     * @var array()
     */
    protected $defaultListeners = array(
        'RouteListener',
        'DispatchListener',
        'SendResponseListener',
    );
}
