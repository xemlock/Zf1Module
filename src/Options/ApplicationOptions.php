<?php

namespace Zf1Module\Options;

use Zend\Stdlib\AbstractOptions;

class ApplicationOptions extends AbstractOptions
{
    /**
     * Application environment
     *
     * @var string
     */
    protected $environment = 'production';

    /**
     * String path to configuration file, or array of configuration options
     *
     * @var array|string
     */
    protected $config = array();

    /**
     * Should warnings be suppressed when a file is not found during autoloading with Zend_Loader_Autoloader instance
     *
     * @var bool
     */
    protected $suppressNotFoundWarnings;

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = (string) $environment;
        return $this;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param array|string $options
     */
    public function setConfig($options)
    {
        $this->config = $options;
        return $this;
    }

    /**
     * @return array|string
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param bool $suppressNotFoundWarnings
     */
    public function setSuppressNotFoundWarnings($suppressNotFoundWarnings)
    {
        $this->suppressNotFoundWarnings = (bool) $suppressNotFoundWarnings;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSuppressNotFoundWarnings()
    {
        return $this->suppressNotFoundWarnings;
    }
}
