<?php

namespace ZeframMvc\Service;

class ResourceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetConfigKey()
    {
        $factory = new ResourceFactory();

        $this->assertEquals('cache_manager', $factory->getConfigKey('CacheManager'));
        $this->assertEquals('cache_manager', $factory->getConfigKey('cacheManager'));
        $this->assertEquals('cache_manager', $factory->getConfigKey('cache_manager'));

        $this->assertEquals('multi_db', $factory->getConfigKey('MultiDB'));
        $this->assertEquals('multi_db', $factory->getConfigKey('MultiDb'));

        $this->assertEquals('xml_http_request', $factory->getConfigKey('XMLHttpRequest'));
        $this->assertEquals('abc_d_efg_hij', $factory->getConfigKey('AbcDEfgHIJ'));
    }
}
