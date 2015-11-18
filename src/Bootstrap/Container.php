<?php

namespace ZeframMvc\Bootstrap;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Container extends \Zend_Registry implements ServiceManagerAwareInterface
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
        parent::__construct(array(), \ArrayObject::ARRAY_AS_PROPS);
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
     * @param string $key
     * @return string
     */
    protected function getResourceKey($key)
    {
        return sprintf('resource.%s', $key);
    }

    /**
     * When accessing services via Bootstrap container resources
     * are checked first, then other services.
     *
     * @param string $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        $resourceKey = $this->getResourceKey($key);
        if ($this->serviceManager->has($resourceKey)) {
            return $this->serviceManager->get($resourceKey);
        }
        return $this->serviceManager->get($key);
    }

    public function offsetSet($key, $value)
    {
        $resourceKey = $this->getResourceKey($key);
        $this->serviceManager->setService($resourceKey, $value);
    }

    public function offsetExists($key)
    {
        $resourceKey = $this->getResourceKey($key);
        return $this->serviceManager->has($resourceKey) || $this->serviceManager->has($key);
    }

    public function offsetUnset($key)
    {
        $resourceKey = $this->getResourceKey($key);
        if ($this->serviceManager->has($resourceKey)) {
            // this may throw depending on service manager settings
            $this->serviceManager->setService($resourceKey, null);
        }
    }
}
