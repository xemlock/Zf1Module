<?php

namespace Zf1Module\Application\Resource;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * This resource provides a way to access ZF2 service locator instance
 * via ZF1 bootstrap plugin resource mechanism. Since it requires a service locator
 * instance passed to constructor, it cannot be initialized by the ZF1 bootstrap itself.
 */
class ServiceLocator
    extends \Zend_Application_Resource_ResourceAbstract
    implements ServiceLocatorAwareInterface
{
    /**
     * Explicit name that this resource class will register as, see:
     * http://framework.zend.com/manual/1.12/en/zend.application.core-functionality.html
     *
     * @var string
     */
    public $_explicitType = 'ServiceLocator';

    /**
     * @var \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @throws \Zend_Application_Resource_Exception
     */
    public function __construct($serviceLocator = null)
    {
        if (!$serviceLocator instanceof ServiceLocatorInterface) {
            throw new \Zend_Application_Resource_Exception(
                'Service locator must be an instance of \Zend\ServiceManager\ServiceLocatorInterface'
            );
        }
        $this->setServiceLocator($serviceLocator);
    }

    /**
     * Set service locator
     *
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return ServiceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get service locator
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Retrieve service locator instance
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function init()
    {
        return $this->getServiceLocator();
    }
}
