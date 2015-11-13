<?php

namespace ZeframMvc\Bootstrap;

use Zend\ServiceManager\ServiceManager;
use ZeframMvc\LegacyApplication;
use ZeframMvc\Bootstrap\Container;
use ZeframMvc\Exception\InvalidArgumentException;
use ZeframMvc\Exception\UnsupportedMethodCallException;

/**
 * Class Bootstrap
 *
 * Bootstrap class provides access to ZF1 resources and container.
 * It is required by ZF1 application resources.
 *
 * @package ZeframMvc\Bootstrap
 */
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Set application
     *
     * Application is set in the constructor, but can be changed afterwards.
     * Each bootstrap instance must have a parent application.
     *
     * @param LegacyApplication $application
     * @return Bootstrap
     * @throws \ZeframMvc\Exception\InvalidArgumentException
     */
    public function setApplication($application)
    {
        if (!$application instanceof LegacyApplication) {
            throw new InvalidArgumentException('Application passed to bootstrap must be an instance of ZeframMvc\LegacyApplication');
        }
        return parent::setApplication($application);
    }

    public function getContainer()
    {
        if (null === $this->_container) {
            /** @var $application LegacyApplication */
            $application = $this->getApplication();
            $this->setContainer(new Container($application->getServiceManager()));
        }
        return $this->_container;
    }

    public function run()
    {
        throw new UnsupportedMethodCallException('Use ZeframMvc\Application::run() to run application');
    }

    protected function _bootstrap($resource = null)
    {
        // no-op due to lazy loading, objects are to be initialized by
        // the service manager upon explicit request
    }
}
