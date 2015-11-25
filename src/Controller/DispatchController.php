<?php

namespace Zf1Module\Controller;

use Zend\Stdlib\DispatchableInterface as Dispatchable;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\RequestInterface;
use Zend\Stdlib\ResponseInterface;

class DispatchController implements Dispatchable, ServiceLocatorAwareInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    protected $serviceLocator;

    /**
     * {@inheritDoc}
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * {@inheritDoc}
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    /**
     * Dispatch a request
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Zend_Controller_Response_Abstract|null
     */
    public function dispatch(RequestInterface $request, ResponseInterface $response = null)
    {
        /** @var $bootstrap \Zend_Application_Bootstrap_Bootstrap */
        $bootstrap = $this->getServiceLocator()->get('Zf1Module\Application')->getBootstrap();
        $bootstrap->bootstrap();

        /** @var $front \Zend_Controller_Front */
        $front = $bootstrap->getResource('FrontController');
        $front->returnResponse(true);

        /** @var $response \Zend_Controller_Response_Abstract */
        $response = $bootstrap->run();
        if (!$response instanceof \Zend_Controller_Response_Abstract) {
            // looks like ZF1 already sent response, so there is nothing more to do
            return;
        }

        return $response;
    }
}
