<?php

namespace ZeframMvc\Options;

use Zend\Stdlib\AbstractOptions as Zf2AbstractOptions;

abstract class AbstractOptions extends Zf2AbstractOptions
{
    protected $__strictMode__ = false;

    protected $__ignoreEmpty__ = true;

    public function toArray()
    {
        $array = array();
        foreach ($this as $key => $value) {
            if ($key === '__strictMode__' || $key === '__ignoreEmpty__') {
                continue;
            }
            if ($this->__ignoreEmpty__ && count($value) === 0) {
                continue;
            }
            $array[strtolower($key)] = $value;
        }
        return $array;
    }
}
