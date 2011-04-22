<?php

/** BOOTSTRAP START **/
set_include_path(
    __DIR__ . PATH_SEPARATOR .
    realpath(__DIR__ . '/../../library') . PATH_SEPARATOR . 
    get_include_path()
);

spl_autoload_register(function($class) {
    //if (strpos($class, 'Zend') !== 0) return;
    $file = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
    return (false !== ($file = stream_resolve_include_path($file))) ? include_once($file) : false;
});
/** BOOTSTRAP END **/




$di = new Zend\Di\DependencyInjector;

$configuration = new Zend\Di\Configuration($di);
$configuration->fromArray(array('definitions' => include './managed-di-container-config.php'));

$repoa = $di->newInstance('My\RepositoryA');
echo $repoa . PHP_EOL;

echo PHP_EOL;

$repob = $di->get('My\RepositoryB');
echo $repob . PHP_EOL;


$entity = $repoa->find();
var_dump($entity);