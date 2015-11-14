<?php

namespace ZeframMvc;

use Zend\Stdlib\Response as Zf2Response;

class Response extends Zf2Response
{
    /**
     * @var \Zend_Controller_Response_Abstract
     */
    protected $response;

    /**
     * @param \Zend_Controller_Response_Abstract $response
     */
    public function __construct(\Zend_Controller_Response_Abstract $response = null)
    {
        if ($response) {
            $this->setResponse($response);
        }
    }

    /**
     * Set response object
     *
     * @param \Zend_Controller_Response_Abstract $response
     */
    public function setResponse(\Zend_Controller_Response_Abstract $response)
    {
        $this->response = $response;
    }

    /**
     * Get response object
     *
     * @return \Zend_Controller_Response_Abstract
     */
    public function getResponse()
    {
        if ($this->response === null) {
            $this->setResponse(new \Zend_Controller_Response_Http());
        }
        return $this->response;
    }

    /**
     * Send the response, including all headers, rendering exceptions if so
     * requested.
     *
     * @return void
     */
    public function send()
    {
        $this->getResponse()->sendResponse();
    }

    public function getContent()
    {
        return $this->getResponse()->getBody();
    }

    public function setContent($value)
    {
        $this->getResponse()->setBody($value);
        return $this;
    }

    public function toString()
    {
        return (string) $this->getResponse();
    }
}
