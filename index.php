<?php

date_default_timezone_set('Europe/Berlin');

if (!isset($_SESSION)) {
    session_start();
}

// 0 => prod, 1 => dev

defined('ENVIRONMENT') || define('ENVIRONMENT', 1);

if (ENVIRONMENT === 1) {
    ini_set('display_errors', 'on');
    error_reporting(-1);
} else {
    ini_set('display_errors', 'off');
    error_reporting(0);
}

// directory separator
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'inc/config.php';
require_once 'App' . DS . 'CustomException.php';

set_exception_handler(['App\CustomException', 'getOutput']);

use App\Core;

$core = new Core();

$core->run();