NoiseLabs PHP ToolKit
=====================

What is this for?
-----------------

This project holds a collection of useful PHP classes grouped in components to be used in any PHP5.3+ project.

Components
----------

* **[ConfigParser](https://github.com/noiselabs/noiselabs-php-toolkit/tree/master/src/NoiseLabs/ToolKit/ConfigParser/README.md)** - A configuration file parser for PHP 5.3 heavily inspired by Python's [configparser](http://docs.python.org/dev/library/configparser.html) library.
* **[Google](https://github.com/noiselabs/noiselabs-php-toolkit/tree/master/src/NoiseLabs/ToolKit/Google)** - Provides a GoogleMaps API to easily build dynamic Google Maps.
* **[Runner](https://github.com/noiselabs/noiselabs-php-toolkit/tree/master/src/NoiseLabs/ToolKit/Runner/README.md)** - A library meant to ease the execution of subprocesses in PHP scripts.

Requirements
------------

* PHP 5.3.2 and up.

License
-------

The NoiseLabs PHP ToolKit is licensed under the LGPLv3 License. See the LICENSE file for details.

Installation
------------

NoiseLabs-PHP-ToolKit is composer-friendly.

### 1. Add the noiselabs/php-toolkit package in your composer.json

```js
{
    "require": {
        "noiselabs/php-toolkit": "dev-master"
    }
}
```

Now tell composer to download this package by running the command:

``` bash
$ php composer.phar update noiselabs/php-toolkit
```

Composer will install the bundle to your project's `vendor/noiselabs` directory.

Documentation
-------------

Each component has it's own `README.md` file with instructions on the usage of the component.

API-level documentation is available under the `doc` folder in `doc/docblox/`.

Development
-----------

### Authors

* Vítor Brandão [ <vitor@noiselabs.org> / [@noiselabs](http://twitter.com/noiselabs) / [blog](http://blog.noiselabs.org) ]

### Submitting bugs and feature requests

Bugs and feature requests are tracked on [GitHub](https://github.com/noiselabs/noiselabs-php-toolkit/issues)