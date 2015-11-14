<?php

namespace ZeframMvc;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class SendResponseListener implements ListenerAggregateInterface
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
        // Zend\Mvc\SendResponseListener has priority -10000, here we need a lower one
        $this->listeners[] = $events->attach(MvcEvent::EVENT_FINISH, array($this, 'sendResponse'), -11000);
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
     * Send the response
     *
     * @param  MvcEvent $event
     * @return void
     */
    public function sendResponse(MvcEvent $event)
    {
        $response = $event->getResponse();

        if (!$response instanceof Response) {
            return;
        }

        $response->send();
        $event->stopPropagation(true);
    }
}
