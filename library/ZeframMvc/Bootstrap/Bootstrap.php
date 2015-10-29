<?php

namespace ZeframMvc\Bootstrap;

use Zend\ServiceManager\ServiceManager;
use ZeframMvc\Bootstrap\Container;
use ZeframMvc\Exception\InvalidArgumentException;
use ZeframMvc\Exception\UnsupportedMethodCallException;


/**
 * Class Bootstrap
 *
 * Bootstrap class provides ZF1 access to resources and resource container.
 * It is required by ZF1 application resources.
 *
 * @package ZeframMvc\Bootstrap
 */
class Bootstrap extends \Zend_Application_Bootstrap_BootstrapAbstract
{
    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $_serviceManager;

    /**
     * @param ServiceManager $serviceLocator
     * @param array $options Application config
     * @throws InvalidArgumentException
     */
    public function __construct($serviceManager, array $options = array())
    {
        if (!$serviceManager instanceof ServiceManager) {
            throw new InvalidArgumentException('Service locator passed to bootstrap must be an instance of \Zend\ServiceManager\ServiceManager');
        }
        $this->_serviceManager = $serviceManager;
        $this->setOptions($options);
    }

    public function getContainer()
    {
        if (null === $this->_container) {
            $this->setContainer(new Container($this->_serviceManager));
        }
        return $this->_container;
    }

    public function hasResource($name)
    {
        // no name mangling as it is entirely done by the service manager
        return $this->_serviceManager->has($name);
    }

    public function getResource($name)
    {
        // no name mangling as it is entirely done by the service manager
        return $this->_serviceManager->get($name);
    }

    protected function _bootstrap($resource = null)
    {
        // no-op due to lazy loading, objects are to be initialized by
        // the service manager upon explicit request
    }

    public function run()
    {
        throw new UnsupportedMethodCallException('Use ZeframMvc\Application::run() to run application');
    }
}
