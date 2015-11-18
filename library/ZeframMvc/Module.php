<?php

namespace ZeframMvc;

use Zend\Console\Console;
use Zend\Mvc\Application as Zf2Application;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\ResponseSender\SendResponseEvent;

class Module
{
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'ZeframMvc\Container'            => 'ZeframMvc\Service\ContainerFactory',
                'ZeframMvc\Bootstrap'            => 'ZeframMvc\Service\BootstrapFactory',
                'ZeframMvc\LegacyApplication'    => 'ZeframMvc\Service\LegacyApplicationFactory',
            ),
            'aliases' => array(
                'Bootstrap'         => 'ZeframMvc\Bootstrap',
                'LegacyApplication' => 'ZeframMvc\LegacyApplication',
            ),
            'abstract_factories' => array(
                'ZeframMvc\Service\ResourceFactory',
            ),
        );
    }

    public function onBootstrap(MvcEvent $e)
    {
        $events = $e->getApplication()->getEventManager();
        $events->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 1000);
        $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 1000);
        $events->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), 1000);
    }

    public function onDispatchError(MvcEvent $e)
    {
        // CLI is also supported
        if ($e->getError() !== Zf2Application::ERROR_ROUTER_NO_MATCH) {
            return;
        }

        // TODO 1. mark as not error, 2. set artificial route match, 3. pass control to ZF1
        // must do this in order for ZendDeveloperToolbar to work
        // $e->setError()

        $e->setParam('dispatch-zf1', true);
        // TODO should event propagation be stopped?
    }

    public function onRender(MvcEvent $e)
    {
        if (!$e->getParam('dispatch-zf1')) {
            return;
        }
        // disable ZF2 rendering if in ZF1 context
        $e->stopPropagation(true);
    }

    public function onFinish(MvcEvent $e)
    {
        if (!$e->getParam('dispatch-zf1')) {
            return;
        }

        $application = $e->getApplication();

        /** @var $bootstrap \Zend_Application_Bootstrap_Bootstrapper */
        $bootstrap = $application->getServiceManager()->get('ZeframMvc\Bootstrap');
        $bootstrap->bootstrap();

        /** @var $front \Zend_Controller_Front */
        $front = $bootstrap->getResource('FrontController');
        $front->returnResponse(true);

        /** @var $response \Zend_Controller_Response_Abstract */
        $response = $bootstrap->run();
        if (!$response instanceof \Zend_Controller_Response_Abstract) {
            // ZF1 sent response, stop this event, there is nothing more to do
            $e->stopPropagation(true);
            return;
        }

        // convert response to HttpResponse, so that it can be available to other ZF2 modules
        // TODO use a specific class for this
        if ($response->isException() && $response->renderExceptions()) {
            $exceptions = '';
            foreach ($this->getException() as $e) {
                $exceptions .= $e->__toString() . "\n";
            }
            $body = $exceptions;
        } else {
            $body = $response->getBody();
        }

        switch (true) {
            case $response instanceof \Zend_Controller_Response_Cli:
                $r = new \Zend\Console\Response();
                $r->setContent($body);
                break;

            default:
                $r = new \Zend\Http\Response();
                $r->setStatusCode($response->getHttpResponseCode());
                $r->setHeaders($this->getHeadersFromResponse($response));
                $type = $r->getHeaders()->get('Content-Type');
                if (!$type) {
                    $r->getHeaders()->addHeaderLine('Content-Type', 'text/html');
                }
                $r->setContent($body);
                break;
        }

        $e->setResponse($r);
        assert($r === $e->getResponse());
        echo __METHOD__, ' setResponse: ', get_class($r), '@', spl_object_hash($r), '<br/>';
    }

    function getHeadersFromResponse(\Zend_Controller_Response_Abstract $response)
    {
        $headers = new \Zend\Http\Headers();

        foreach ($response->getRawHeaders() as $header) {
            $headers->addHeaderLine($header);
        }

        foreach ($response->getHeaders() as $header) {
            $headers->addHeaderLine($header['name'], $header['value']);
        }

        return $headers;
    }
}
