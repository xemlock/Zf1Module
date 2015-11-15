<?php

namespace ZeframMvc;

use Zend\Mvc\MvcEvent;
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
 * This is a minimum setup to run ZF1 applications with ServiceManager and
 * ModuleManager. To run add this to application.config.php:
 *
 *      'service_manager' => array(
 *          'factories' => array(
 *              'ModuleManager' => 'ZeframMvc\Service\ModuleManagerFactory',
 *              'ServiceListener' => 'ZeframMvc\Service\ServiceListenerFactory',
 *          ),
 *      ),
 *
 * @package    ZeframMvc
 */
class Application extends \Zend\Mvc\Application
{
    /**
     * No default listeners, they must be added explicitly in modules
     * @var array()
     */
    protected $defaultListeners = array();

    /**
     * Run the application
     *
     * @return void
     */
    public function run()
    {
        $event = $this->getMvcEvent();

        // Define callback used to determine whether or not to short-circuit
        $shortCircuit = function ($r) use ($event) {
            if ($r instanceof ResponseInterface) {
                return true;
            }
            return false;
        };

        $events = $this->getEventManager();
        $result = $events->trigger(MvcEvent::EVENT_DISPATCH, $event, $shortCircuit);

        // Complete response
        $response = $result->last();
        if ($response instanceof ResponseInterface) {
            $event->setTarget($this);
            $event->setResponse($response);
            $events->trigger(MvcEvent::EVENT_FINISH, $event);
        }
    }
}
