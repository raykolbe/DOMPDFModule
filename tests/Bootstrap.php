<?php

use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfiguration;

error_reporting( E_ALL | E_STRICT );

chdir(__DIR__);

$previousDir = '.';
while (!file_exists('config/application.config.php')) {
    $dir = dirname(getcwd());
    if($previousDir === $dir) {
        throw new RuntimeException(
            'Unable to locate "config/application.config.php": ' .
            'is DOMPDFModule in a subdir of your application skeleton?'
        );
    }
    $previousDir = $dir;
    chdir($dir);
}

if (is_readable(__DIR__ . '/TestConfiguration.php')) {
    $configuration = include_once __DIR__ . '/TestConfiguration.php';
} else {
    $configuration = include_once __DIR__ . '/TestConfiguration.php.dist';
}

// Assumes PHP Composer autoloader w/compiled classmaps, etc.
require_once('vendor/autoload.php');

$serviceManager = new ServiceManager(new ServiceManagerConfiguration($configuration['service_manager']));
$serviceManager->setService('ApplicationConfiguration', $configuration);
$serviceManager->setAllowOverride(true);

$moduleManager = $serviceManager->get('ModuleManager');
$moduleManager->loadModules();
