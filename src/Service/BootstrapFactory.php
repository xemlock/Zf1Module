<?php

namespace Zf1Module\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class BootstrapFactory implements FactoryInterface
{
    /**
     * Retrieve a bootstrapped ZF1 application bootstrap
     *
     * To access Bootstrap instance without bootstrapping it use
     * <code>$serviceLocator->get('Zf1Module\Application')->getBootstrap()</code>
     *
     * @param  ServiceLocatorInterface $serviceLocator
     * @return \Zend_Application_Bootstrap_BootstrapAbstract
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $application \Zend_Application */
        $application = $serviceLocator->get('Zf1Module\Application');

        $bootstrap = $application->getBootstrap();
        $bootstrap->bootstrap();

        return $bootstrap;
    }
}
