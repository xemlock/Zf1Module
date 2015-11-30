Zf1Module
=========

Zf1Module allows running ZF1 application in Zend Framework 2.

## Installation

Via composer

## Configuration

Add `Zf1Module` to the module list in `config/application.config.php` and see the magic happens. Almost, because you need to configure it first:

```php
'zf1' => array(
    'environment' => 'production',
    'config' => 'path/to/application.ini',
)
```

## Usage

By default the ZF1 application will be an instance of `\Zend_Application`. You can override this class by providing `application_class` setting.
The only requirement is that this class constructor must follow the same arguments as that of `\Zend_Application`.

When instantiating application a `ServiceLocator` plugin resource is registered at bootstrap. This allows
accessing ZF2 service locator in ZF1 in the following way:

```php
Zend_Controller_Front::getInstance()->getParam('bootstrap')->getResource('ServiceLocator');
```

To access ZF1 resources in ZF2 code you can retrieve the bootstrap instance with:

```php
$serviceManager->get('Zf1Module\Application')->getBootstrap();
```

