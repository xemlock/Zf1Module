<?php

namespace ZeframMvc\Bootstrap;

use ZeframMvc\Exception\UnsupportedMethodCallException;

/**
 * Class Bootstrap
 *
 * Bootstrap class provides access to ZF1 resources and container.
 * It is required by ZF1 application resources.
 *
 * @package ZeframMvc\Bootstrap
 */
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
    protected function _bootstrap($resource = null)
    {
        // no-op due to lazy loading, objects are to be initialized by
        // the service manager upon explicit request
    }
}
