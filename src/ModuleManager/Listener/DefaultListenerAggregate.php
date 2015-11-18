<?php

namespace ZeframMvc\ModuleManager\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Listener\ConfigMergerInterface;
use Zend\ModuleManager\Listener\DefaultListenerAggregate as Zf2DefaultListenerAggregate;
use Zend\ModuleManager\Listener\LocatorRegistrationListener;
use Zend\ModuleManager\Listener\ModuleResolverListener;
use Zend\ModuleManager\Listener\AutoloaderListener;
use Zend\ModuleManager\Listener\ModuleDependencyCheckerListener;
use Zend\ModuleManager\Listener\InitTrigger;
use Zend\ModuleManager\Listener\OnBootstrapListener;
use Zend\ModuleManager\ModuleEvent;

/**
 * Default Listener Aggregate
 *
 * Attaches a customized ModuleLoaderListener that can properly handle
 * ZF1 naming conventions of module directories.
 *
 * The code is directly copied from Zend\ModuleManager\Listener\DefaultListenerAggregate::attach()
 * - which hasn't changed since 15 Jan 2013 (zendframework/zend-modulemanager@4684ed2).
 * Please note that classes are resolved differently due to used 'use' directives.
 */
class DefaultListenerAggregate extends Zf2DefaultListenerAggregate
{
    public function attach(EventManagerInterface $events)
    {
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

    public function getConfigListener()
    {
        if (!$this->configListener instanceof ConfigMergerInterface) {
            $this->setConfigListener(new ConfigListener($this->getOptions()));
        }
        return $this->configListener;
    }
}