<?php

namespace ZeframMvc;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;

class DispatchListener implements ListenerAggregateInterface
{
    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach listeners to an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 1000);
        $this->listeners[] = $events->attach(MvcEvent::EVENT_DISPATCH_ZF1, array($this, 'onDispatch'));
    }

    /**
     * Detach listeners from an event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Listen to the "dispatch" event
     *
     * @param  \Zend\Mvc\MvcEvent $e
     * @return mixed
     */
    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        /** @var $front \Zend_Controller_Front */
        $front = $sm->get('ZeframMvc\Bootstrap')->getResource('FrontController');
        $front->returnResponse(true); // Response must be always returned
        $response = new Response($front->dispatch());
        return $this->complete($response, $e);
    }

    /**
     * Complete the dispatch
     *
     * @param  mixed $return
     * @param  \Zend\Mvc\MvcEvent $event
     * @return mixed
     */
    protected function complete($return, \Zend\Mvc\MvcEvent $event)
    {
        $event->setResult($return);
        return $return;
    }
}
