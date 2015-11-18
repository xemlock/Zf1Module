<?php

namespace ZeframMvc\Bootstrap;

/**
 * Class Bootstrap
 *
 * Bootstrap class provides access to ZF1 resources and container.
 * It is required by ZF1 application resources.
 *
 * @package ZeframMvc\Bootstrap
 * @deprecated
 */
class Bootstrap extends \Zend_Application_Bootstrap_Bootstrap
{
}

/*

Lazy loading analysis:

Cachemanager:
- can be lazy loaded

Db:
- Zend_Db_Table::setDefaultMetadataCache()
- Zend_Db_Table::setDefaultAdapter()
- may bootstrap Cachemanager

Dojo:
- bootstraps View
- registers dojo view helper

Frontcontroller:
- Zend_Controller_Action_HelperBroker::addPath()

Layout:
- bootstraps Frontcontroller
- registers controller plugin
- registers 'layout' action helper
- may use view form ViewRenderer helper

Locale:
- registers in Zend_Registry at 'Zend_Locale' key
- may register as default locale via Zend_Locale::setDefault()
- may bootstrap Cachemanager
- may set cache via Zend_Locale::setCache()

Log:
- can be lazy loaded

Mail:
- may be set as default transport via Zend_Mail::setDefaultTransport()
- may set transport defaults via Zend_Mail::setDefault{From,ReplyTo}()

Modules:
- bootstraps Frontcontroller

Multidb:
- may bootstrap Cachemanager
- Zend_Db_Table::setDefaultMetadataCache()
- Zend_Db_Table::setDefaultAdapter()

Navigation:
- may set Zend_Navigation_Page::setDefaultPageType()
- may register in Zend_Registry at 'Zend_Navigation' key or bootstrap view
  and registers in navigation helper

Router:
- bootstraps Frontcontroller
- initializes (not creates) front controller's router

Session:
- may register save handler Zend_Session::setSaveHandler()
- may set any session options

Translate:
- may bootstrap Cachemanager
- registers in Zend_Registry at 'Zend_Translate' (or key specified by 'registry_key' option)
- this may affect translate view helper

Useragent:
- bootstrap view if available and registers in userAgent view helper

View:
- configures view helpers

Jquery (ZendX):
- bootstraps view
- sets up ZendX_JQuery_View_Helper_JQuery

Lazy loading ZF1 resources will bring more trouble than it is worth.

 */