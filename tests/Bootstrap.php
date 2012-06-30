<?php

use Zend\Loader\AutoloaderFactory;

error_reporting( E_ALL | E_STRICT );

$zfCoreLibrary = realpath(dirname(__DIR__)) . "/../../zendframework/zendframework/library";
$coreTests     = realpath(dirname(__DIR__)) . "/tests";

$path = array(
    $zfCoreLibrary,
    $coreTests,
    get_include_path(),
);

set_include_path(implode(PATH_SEPARATOR, $path));

/**
 * Setup autoloading - Based on PHP Composer autoload.
 */

chdir(dirname(__DIR__));

if (file_exists(dirname(__DIR__) . '/../../autoload.php')) {
    $loader = include dirname(__DIR__) . '/../../autoload.php';
}

/*
 * Load the user-defined test configuration file, if it exists; otherwise, load
 * the default configuration.
 */
//if (is_readable($zfCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php')) {
//    require_once $zfCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php';
//} else {
//    require_once $zfCoreTests . DIRECTORY_SEPARATOR . 'TestConfiguration.php.dist';
//}

unset($coreTests, $zfCoreLibrary, $path);
