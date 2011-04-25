<?php

/** BOOTSTRAP START **/
set_include_path(
    __DIR__ . PATH_SEPARATOR .
    realpath(__DIR__ . '/../../library') . PATH_SEPARATOR . 
    get_include_path()
);

spl_autoload_register(function($class) {
    if (strpos($class, 'Zend') !== 0) return;
    $file = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
    return (false !== ($file = stream_resolve_include_path($file))) ? include_once($file) : false;
});
/** BOOTSTRAP END **/
    
$configPath = $_SERVER['argv'][1];
if (!file_exists($configPath)) {
    die('Zend\Di\Framework configuration file not found in ' . $configPath);
}

$di = new Zend\Di\DependencyInjector();

$diFrameworkConfiguration = new Zend\Di\Framework\Configuration();
$diFrameworkConfiguration->fromArray(include $configPath);

$diFramework = new Zend\Di\Framework($diFrameworkConfiguration, $di);
$diFramework->build();

