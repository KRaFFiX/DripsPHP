<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 24.01.15 - 15:22.
 */
use DripsPHP\App;
use DripsPHP\ClassLoader\ClassLoader;
use DripsPHP\CLI\DripsCLI;
use DripsPHP\Debug\ErrorHandler;
use DripsPHP\Debug\ExceptionHandler;
use DripsPHP\API\Shutdown;

// init session
session_set_cookie_params(0, '/');
session_start();

// constants
define('CLI', PHP_SAPI == 'cli');

// register ClassLoader
include 'core/lib/ClassLoader/ClassLoader.class.php';
spl_autoload_register(function ($class) {
    ClassLoader::load($class);
});

// register ShutdownHook
register_shutdown_function(function () {
    App::shutdown();
});

// load application
if (CLI) {
    App::configuration();
    DripsCLI::init($argv);
} else {
    // register ErrorHandler
    set_error_handler(function ($errno, $errstr, $errfile, $errline, $errcontext) {
        ErrorHandler::handle($errno, $errstr, $errfile, $errline, $errcontext);
    });

    // register ExceptionHandler
    set_exception_handler(function ($exception) {
        ExceptionHandler::handle($exception);
    });
    // run application
    App::run();
}
