<?php

namespace ZeframMvc\Router;

use Zend\Mvc\Router\RouteStackInterface;
use Zend\Stdlib\RequestInterface as Request;

class EmptyRouteStack implements RouteStackInterface
{
    public static function factory($options = array())
    {
        return null;
    }

    public function match(Request $request)
    {
        return null;
    }

    public function assemble(array $params = array(), array $options = array())
    {
        if (!isset($options['name'])) {
            throw new \Zend\Mvc\Router\Exception\InvalidArgumentException('Missing "name" option');
        }

        throw new \Zend\Mvc\Router\Exception\RuntimeException(sprintf('Route with name "%s" not found', $options['name']));
    }

    public function addRoute($name, $route, $priority = null)
    {
        return $this;
    }

    public function addRoutes($routes)
    {
        return $this;
    }

    public function removeRoute($name)
    {
        return $this;
    }

    public function setRoutes($routes)
    {
        return $this;
    }
}
