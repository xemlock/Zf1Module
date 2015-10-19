<?php

namespace ZeframMvc\ModuleManager\Listener;

use ZeframMvc\Loader\ModuleAutoloader;
use Zend\ModuleManager\Listener\ModuleLoaderListener as Zf2ModuleLoaderListener;
use Zend\ModuleManager\Listener\AbstractListener;
use Zend\ModuleManager\Listener\ListenerOptions;

class ModuleLoaderListener extends Zf2ModuleLoaderListener
{
    /**
     * {@inheritDoc}
     */
    public function __construct(ListenerOptions $options = null)
    {
        // Skip parent constructor to avoid instantiation of moduleLoader,
        // that will be replaced by a customized one. This is an ugly
        // workaround for performance reasons.
        AbstractListener::__construct($options);

        $this->generateCache = $this->options->getModuleMapCacheEnabled();
        $this->moduleLoader  = new ModuleAutoloader($this->options->getModulePaths());

        if ($this->hasCachedClassMap()) {
            $this->generateCache = false;
            $this->moduleLoader->setModuleClassMap($this->getCachedConfig());
        }
    }
}
