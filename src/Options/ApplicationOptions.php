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
     * @var array|string|\Zend_Config
     */
    protected $options = array();

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
     * @param array|string|\Zend_Config $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return array|string|\Zend_Config
     */
    public function getOptions()
    {
        return $this->options;
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
