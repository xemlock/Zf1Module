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
     * @param ServiceManager $serviceLocator
     * @param array $options Application config
     * @throws InvalidArgumentException
     */
    public function __construct($serviceManager, array $options = array())
    {
        if (!$serviceManager instanceof ServiceManager) {
            throw new InvalidArgumentException('Service locator passed to bootstrap must be an instance of \Zend\ServiceManager\ServiceManager');
        }
        $this->setContainer(new Container($serviceManager));
        $this->setOptions($options);
    }

    protected function _bootstrap($resource = null)
    {
        // no-op due to lazy loading, objects are initialized by
        // factories, when retrieved from container
    }

    public function run()
    {
        throw new UnsupportedMethodCallException('Use \ZeframMvc\Application::run() to run application');
    }
}
