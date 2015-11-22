<?php

namespace Zf1Module;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Application extends \Zend_Application implements ServiceManagerAwareInterface
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * Constructor
     *
     * Initialize application. Potentially initializes include_paths, PHP
     * settings, and bootstrap class.
     *
     * @param ServiceManager $serviceManager
     * @param string $environment
     * @param string|array|\Zend_Config $options
     * @param bool $suppressNotFoundWarnings
     */
    public function __construct(ServiceManager $serviceManager, $environment = null, $options = null, $suppressNotFoundWarnings = null)
    {
        $this->setServiceManager($serviceManager);
        parent::__construct($environment, $options, $suppressNotFoundWarnings);
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Get service manager
     *
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
}
