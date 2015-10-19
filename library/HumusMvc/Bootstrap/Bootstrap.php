<?php

namespace HumusMvc\Bootstrap;

use Zend\ServiceManager\ServiceManager;
use HumusMvc\Bootstrap\Container;
use HumusMvc\Exception\InvalidArgumentException;
use HumusMvc\Exception\UnsupportedMethodCallException;


/**
 * Class Bootstrap
 *
 * Bootstrap class provides ZF1 access to resources and resource container.
 * It is required by ZF1 application resources.
 *
 * @package HumusMvc\Bootstrap
 */
class Bootstrap extends \Zend_Application_Bootstrap_BootstrapAbstract
{
    /**
     * @param ServiceManager $serviceLocator
     * @param array $config Application config
     * @throws InvalidArgumentException
     */
    public function __construct($serviceManager, array $config = array())
    {
        if (!$serviceManager instanceof ServiceManager) {
            throw new InvalidArgumentException('Service locator passed to bootstrap must be an instance of \Zend\ServiceManager\ServiceManager');
        }
        $this->setContainer(new Container($serviceManager));
        $this->setOptions($config);
    }

    protected function _bootstrap($resource = null)
    {
        // no-op due to lazy loading, objects are initialized by
        // factories, when retrieved from container
    }

    public function run()
    {
        throw new UnsupportedMethodCallException('Use \HumusMvc\Application::run() to run application');
    }
}
