<?php

namespace Zf1Module\Controller;

use Zend\Mvc\Controller\AbstractController;
use Zend\Mvc\MvcEvent;

class DispatchController extends AbstractController
{
    /**
     * Execute the request
     *
     * @param \Zend\Mvc\MvcEvent $event
     * @return \Zend_Controller_Response_Abstract|null
     */
    public function onDispatch(MvcEvent $event)
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
            $event->stopPropagation();
            return;
        }

        $event->setResult($response);
    }
}
