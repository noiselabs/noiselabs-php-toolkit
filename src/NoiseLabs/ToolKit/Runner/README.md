Runner - Simple subprocess management for PHP 5.3
=================================================

What is Runner?
---------------

Runner is a library meant to ease the execution of subprocesses in PHP scripts. This is an object-oriented approach to use `proc_open` and friends. And, you'll get a ProcessManager for free ;)

Requirements
============

* PHP 5.3.2 and up.

License
========

Runner is licensed under the LGPLv3 License. See the LICENSE file for details.

Installation
============

Cloning/downloading from [GitHub](https://github.com/noiselabs/noiselabs-php-toolkit) is, so far, the only available method to get this library.

You may clone via git:

	$ git clone git://github.com/noiselabs/noiselabs-php-toolkit.git

or download a tarball either in Gzip or Zip format:

	https://github.com/noisebleed/noiselabs-php-toolkit/archives/master

Documentation
==============

Basic instructions on the usage of the library are presented below.

API-level documentation is available under the `doc` folder in `doc/docblox/`.

Usage
-----

### Autoloading classes (optional)

Runner makes use of PHP namespaces and as such the usage of a autoloader libray is recommended. [Symfony](https://github.com/symfony/symfony) provides a great class loader available on [GitHub](https://github.com/symfony/ClassLoader).

To have Symfony's ClassLoader autoloading our classes create a `autoload.php` file  and included it at the top of your scripts.

	<?php
	// autoload.php

	require_once '/path/to/src/Symfony/Component/ClassLoader/UniversalClassLoader.php';

	use Symfony\Component\ClassLoader\UniversalClassLoader;

	$loader = new UniversalClassLoader();
	$loader->registerNamespaces(array(
		'NoiseLabs' => '/path/to/noiselabs-php-toolkit/src',
	));
	$loader->register();

	?>

### Basic usage

To quickstart let's run `ls` from a web script:

	<?php

	namespace Your\Namespace;

	use NoiseLabs\ToolKit\Runner\Process;

	$proc = new Process('ls -l');

	$proc->exec();

	if (0 === $this->getReturnCode()) {
		echo $proc->getOutput();
		/*
		* prints:
		* apc.php app.php check.php config.php favicon.ico robots.txt
		*/
	}

	?>

Development
===========

Authors
-------

* Vítor Brandão - <noisebleed@noiselabs.org> / [twitter](http://twitter.com/noiselabs) / [blog](http://blog.noiselabs.org)

Submitting bugs and feature requests
------------------------------------

Bugs and feature requests are tracked on [GitHub](https://github.com/noiselabs/noiselabs-php-toolkit/issues).