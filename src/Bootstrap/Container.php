<?php

namespace ZeframMvc\Bootstrap;

use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

/**
 * Class Container
 *
 * Services saved to ServiceManager via this container have prefixed key. It is
 * because it is assumed that services saved in that way are ZF1 resources, and
 * this prefix is added to tell them apart from ZF2 services in case of name
 * conflict, i.e. ZF1 router and ZF2 router. The former (ZF1) is stored at
 * '[PREFIX}router', the other one (ZF2) at 'router'.
 *
 * $container->{service} is equivalent to $serviceManager->get("PREFIXservice")
 *
 * This class extends Zend_Registry, so it can be set as a global registry
 * instance via Zend_Registry::setInstance().
 *
 * @package ZeframMvc\Bootstrap
 */
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
        return sprintf('resource.%s', strtolower($key));
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
            // this may throw depending on the value of allowOverride setting
            $this->serviceManager->setService($resourceKey, null);
        }
    }
}
