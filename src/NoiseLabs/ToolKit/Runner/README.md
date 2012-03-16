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

    $proc->run();

    if (0 === $this->getReturnCode()) {
        echo $proc->getOutput();
        // prints: apc.php app.php check.php favicon.ico robots.txt
    }
    else {
        echo 'Execution failed with error: '.$proc->getErrorMessage();
    }

    ?>

### Configuring Runner behaviour

The Process class is configurable through the `$settings` public variable. This allows us, for instance, to change the current working directory or to run every command as `sudo`.

**Known settings**:

- 'sudo': If TRUE, prepend every command with 'sudo'.
- 'cwd': The initial working dir for the command. This must be an absolute directory path, or NULL if you want to use the default value (the working dir of the current PHP process).
- 'env': An array with the environment variables for the command that will be run, or NULL to use the same environment as the current PHP process.

**Usage**:

    $proc = new Process('ls -l');

    // change the current working directory
    $proc->settings->set('cwd', '/usr/share/php');

    // now execute with these new settings
    $proc->run();

### Using a custom logger

By default Process will use `error_log()` to record it's messages. To override the original logger method just extend Process and replace `Process::log()` with your own implementation.

[Monolog](https://github.com/Seldaek/monolog) is a great logging library for PHP 5.3 and will be used as our custom logger in the following example.

    <?php

    namespace Your\Namespace;

    use NoiseLabs\ToolKit\Runner\Process;
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    class MyProcess extends Process
    {
        protected $logger;

        public function __construct($command, array $settings = array())
        {
            parent::__construct($command, $settings);

            // create a log channel
            $this->logger = new Logger('Runner');
            $this->logger->pushHandler(new StreamHandler('path/to/your.log', Logger::WARNING));
        }

        public function log($message, $level = null)
        {
            // add records to the log
            $this->logger->info($message);
        }
    }

    ?>

### ProcessManager

The ProcessManager class is a container for Process objects that allows a _set-once-set-all_ procedure and easy reutilization of commands.

    $procman = new ProcessManager();

    // apply settings for every process registered in this object
    $procman->settings->set('sudo', true);

    // now, add some commands...
    $procman->
        add('users', new Process('users'))->
        add('uptime', new Process('uptime'))->
        add('free', new Process('free -m'));

    // ...and run 'free'
    $procman->get('free')->run();

Development
===========

Authors
-------

* Vítor Brandão - <noisebleed@noiselabs.org> / [twitter](http://twitter.com/noiselabs) / [blog](http://blog.noiselabs.org)

Submitting bugs and feature requests
------------------------------------

Bugs and feature requests are tracked on [GitHub](https://github.com/noiselabs/noiselabs-php-toolkit/issues).
