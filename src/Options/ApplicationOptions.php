<?php

namespace Zf1Module\Options;

use Zend\Stdlib\AbstractOptions;

class ApplicationOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $environment = 'production';

    /**
     * @var array|string
     */
    protected $config = array();

    /**
     * @var bool
     */
    protected $suppressNotFoundWarnings;

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
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
        $this->suppressNotFoundWarnings = $suppressNotFoundWarnings;
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
