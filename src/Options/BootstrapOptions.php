<?php

namespace Zf1Module\Options;

use Zf1Module\Exception\InvalidArgumentException;
use Zend\Stdlib\ArrayUtils;

// this is to avoid processing of whole config array in bootstrap. Only
// relevant keys will be provided. And also a proper order will be maintained
// i.e. PluginLoader will be before pluginPaths
// This options must be understandable by ZF1 Bootstrap, so key normalization
// is different.
class BootstrapOptions extends AbstractOptions
{
    /**
     * @var array
     */
    protected $resources = array();

    protected $pluginLoader;

    /**
     * @var array
     */
    protected $pluginPaths = array();

    protected $resourceLoader;

    /**
     * @param array|\Traversable $resources
     */
    public function setResources($resources)
    {
        if ($resources instanceof \Traversable) {
            $resources = ArrayUtils::iteratorToArray($resources);
        }
        if (!is_array($resources)) {
            throw new InvalidArgumentException(sprintf(
                'Parameter provided to %s must be an array or Traversable',
                __METHOD__
            ));
        }
        foreach ($resources as $key => $value) {
            if (is_string($key) && $key !== ($normalizedKey = strtolower($key))) {
                $resources[$normalizedKey] = ArrayUtils::merge(
                    $resources[$normalizedKey],
                    $value
                );
            }
        }
        $this->resources = $resources;
    }

    /**
     * @return array
     */
    public function getResources()
    {
        return $this->resources;
    }

    public function setPluginPaths($pluginPaths)
    {
        foreach ($pluginPaths as $prefix => $path) {
            $this->pluginPaths[$prefix] = $path;
        }
        return $this;
    }

    public function getPluginPaths()
    {
        return $this->pluginPaths;
    }
}