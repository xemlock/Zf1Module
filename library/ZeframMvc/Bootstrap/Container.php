<?php

namespace ZeframMvc\Bootstrap;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Container implements ServiceManagerAwareInterface
{
    /**     
     * @var ServiceManager
     */
    protected $serviceManager;

    /**
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->setServiceManager($serviceManager);
    }

    /**
     * @param ServiceManager $serviceManager
     * @return $this
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * @return ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * When accessing services via Bootstrap container resources
     * are checked first, then other services.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        $resourceKey = $this->getResourceKey($key);
        if ($this->serviceManager->has($resourceKey)) {
            return $this->serviceManager->get($resourceKey);
        }
        return $this->serviceManager->get($key);
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $resourceKey = $this->getResourceKey($key);
        $this->serviceManager->setService($resourceKey, $value);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        $resourceKey = $this->getResourceKey($key);
        return $this->serviceManager->has($resourceKey) || $this->serviceManager->has($key);
    }

    /**
     * @param string $key
     */
    public function __unset($key)
    {
        $resourceKey = $this->getResourceKey($key);
        if ($this->serviceManager->has($resourceKey)) {
            // this may throw depending on service manager settings
            $this->serviceManager->setService($resourceKey, null);
        }
    }

    /**
     * @param string $key
     * @return string
     */
    protected function getResourceKey($key)
    {
        return sprintf('resource.%s', $key);
    }
}

