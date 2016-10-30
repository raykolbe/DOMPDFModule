<?php

use DOMPDFModuleTest\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Zend\Loader\StandardAutoloader;

error_reporting(E_ALL | E_STRICT);

if (is_readable(__DIR__ . '/TestConfiguration.php')) {
    $configuration = include_once __DIR__ . '/TestConfiguration.php';
} else {
    $configuration = include_once __DIR__ . '/TestConfiguration.php.dist';
}

// Assumes PHP Composer autoloader w/compiled classmaps, etc.
require_once __DIR__ . '/../vendor/autoload.php';

// This namespace is not in classmap.
$loader = new StandardAutoloader(
                array(
                    StandardAutoloader::LOAD_NS => array(
                        'DOMPDFModuleTest' => __DIR__ . '/DOMPDFModuleTest'
                    ),
        ));
$loader->register();

$serviceManager = new ServiceManager(new ServiceManagerConfig($configuration['service_manager']));
$serviceManager->setService('ApplicationConfig', $configuration);
$serviceManager->setAllowOverride(true);

$moduleManager = $serviceManager->get('ModuleManager');
$moduleManager->loadModules();

TestCase::setServiceManager($serviceManager);