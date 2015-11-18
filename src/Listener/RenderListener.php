<?php

namespace Zf1Module\Listener;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\ResponseInterface as Response;

class RenderListener extends AbstractListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 1000);
    }

    /**
     * Renders ZF1 response into a ZF2 response
     *
     * @param MvcEvent $e
     * @return Response|null
     */
    public function onRender(MvcEvent $e)
    {
        if (!$e->getResult() instanceof \Zend_Controller_Response_Abstract) {
            return;
        }

        $response = $e->getResponse();
        $this->renderIntoResponse($response, $e->getResult());

        $e->setResult($response);
        return $response;
    }

    public function renderIntoResponse(Response $r, \Zend_Controller_Response_Abstract $response)
    {
        // render ZF1 response into ZF2 response
        if ($response->isException() && $response->renderExceptions()) {
            $exceptions = '';
            foreach ($response->getException() as $e) {
                $exceptions .= $e . "\n";
            }
            $body = $exceptions;
        } else {
            $body = $response->getBody();
        }

        switch (true) {
            case $r instanceof \Zend\Http\Response:
                /** @var $r \Zend\Http\Response */
                $r->setStatusCode($response->getHttpResponseCode());
                $r->setHeaders($this->getHeadersFromResponse($response));

                if ($response instanceof \Zend_Controller_Response_Http) {
                    $type = $r->getHeaders()->get('Content-Type');
                    if (!$type) {
                        $r->getHeaders()->addHeaderLine('Content-Type', 'text/html');
                    }
                }
                $r->setContent($body);
                break;

            case $r instanceof \Zend\Console\Response:
            default:
                /** @var $r \Zend\Console\Response */
                $r->setContent($body);
                break;
        }
    }

    protected function getHeadersFromResponse(\Zend_Controller_Response_Abstract $response)
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
