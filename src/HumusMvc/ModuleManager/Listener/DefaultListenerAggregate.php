<?php

namespace HumusMvc\ModuleManager\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Listener\DefaultListenerAggregate as Zf2DefaultListenerAggregate;
use Zend\ModuleManager\Listener\LocatorRegistrationListener;
use Zend\ModuleManager\Listener\AutoloaderListener;
use Zend\ModuleManager\Listener\ModuleDependencyCheckerListener;
use Zend\ModuleManager\Listener\InitTrigger;
use Zend\ModuleManager\Listener\OnBootstrapListener;
use Zend\ModuleManager\ModuleEvent;

/**
 * Default Listener Aggregate
 *
 * Attaches a customized ModuleLoaderListener and ModuleResolverListener
 * that can properly handle ZF1 module naming conventions.
 */
class DefaultListenerAggregate extends Zf2DefaultListenerAggregate
{
    /**
     * {@inheritDoc}
     */
    public function attach(EventManagerInterface $events)
    {
        // The code below is directly copied from Zend\ModuleManager\Listener\DefaultListenerAggregate::attach()
        // - which hasn't changed since 15 Jan 2013 (zendframework/zend-modulemanager@4684ed2).
        // Please note that ModuleLoaderListener and ModuleResolverListener classes used are not the original
        // ones due to used use directives.

        $options                     = $this->getOptions();
        $configListener              = $this->getConfigListener();
        $locatorRegistrationListener = new LocatorRegistrationListener($options);

        // High priority, we assume module autoloading (for FooNamespace\Module classes) should be available before anything else
        $this->listeners[] = $events->attach(new ModuleLoaderListener($options));
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE_RESOLVE, new ModuleResolverListener);
        // High priority, because most other loadModule listeners will assume the module's classes are available via autoloading
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE, new AutoloaderListener($options), 9000);

        if ($options->getCheckDependencies()) {
            $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE, new ModuleDependencyCheckerListener, 8000);
        }

        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE, new InitTrigger($options));
        $this->listeners[] = $events->attach(ModuleEvent::EVENT_LOAD_MODULE, new OnBootstrapListener($options));
        $this->listeners[] = $events->attach($locatorRegistrationListener);
        $this->listeners[] = $events->attach($configListener);
        return $this;
    }
}
