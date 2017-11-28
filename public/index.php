<?php

// set the application environment
define('ENVIRONMENT', 'development');

if (defined('ENVIRONMENT') && ENVIRONMENT === 'development') {
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', true);
}

// set charset
ini_set('default_charset', 'utf-8');

// change working directory to project root, makes our lives easier when dealing with paths
chdir(dirname(__DIR__));

// define application path
define('APPLICATION_PATH', realpath(__DIR__ . '/../'));

// setup autoloading
require APPLICATION_PATH . '/vendor/autoload.php';

// initialize the API with the given dependencies, then run it
$api = new Which1ispink\API\Core\Api(require APPLICATION_PATH . '/config/application.config.php');
$api->run();
