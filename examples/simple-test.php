<?php

/** BOOTSTRAP START **/
set_include_path(
    realpath('../library') . PATH_SEPARATOR . 
    get_include_path()
);

spl_autoload_register(function($class) {
    $file = str_replace(array('\\', '_'), DIRECTORY_SEPARATOR, $class) . '.php';
    return (false !== ($file = stream_resolve_include_path($file))) ? include_once($file) : false;
});
/** BOOTSTRAP START **/


$di = new Zend\Di\DependencyInjector;

/**
 * From this point on, this is a valid usage of Matthew's Di Prototype
 * Places where I think we can reduce the amount of "setup code" are
 * wrapped inside curly brances { }
 */


/**
 * This whole section of code would be replace by something like this:
 * $diFramework = new Zend\Di\Framework();
 * $diFramework->setDi($di);
 * $diFramework->setConfiguration('framework-config.ini'); // or xml, or yaml
 * $diFramework->bootstrap();
 */ 
{
    /**
     * this should be automatic (via reflection) replace by something like this:
     * $diFramework->addManagedNamespace('My'); //optional: add seed path - path/to/My
     */
    $def1 = new Zend\Di\Definition('My\DbAdapter');
    $def1->setParamMap(array('username' => 0, 'password' => 1));
    
    $def2 = new Zend\Di\Definition('My\Mapper');
    $def2->setParamMap(array('dbAdapter' => 0));
    $def2->setParams(array('dbAdapter' => new Zend\Di\Reference('dbAdapter')));

    $def3 = new Zend\Di\Definition('My\RepositoryA');
    $def3->setParamMap(array('mapper' => 0));
    $def3->setParams(array('mapper' => new Zend\Di\Reference('mapper')));

    $def4 = new Zend\Di\Definition('My\RepositoryB');
    $def4->setParamMap(array('mapper' => 0));
    $def4->setParams(array('mapper' => new Zend\Di\Reference('mapper')));
    
    // back to $def1 becuase it contains values for parameters
    $def1->setParams(array('password' => 'bar', 'username' => 'foo'));
    
    // make sure to pass the definitions into the actual di container
    $di->setDefinition($def1, 'dbAdapter');
    $di->setDefinition($def2, 'mapper');
    $di->setDefinition($def3, 'repositoryA');
    $di->setDefinition($def4, 'repositoryB');
}



/**
 * Same as:
 * $repoa = new My\RepositoryA(new My\Mapper(new My\DbAdapter('foo', 'bar')));
 */
$repoa = $di->newInstance('repositoryA'); 
echo $repoa;

echo PHP_EOL;
echo PHP_EOL;

$repob = $di->newInstance('repositoryB');
echo $repob;

echo PHP_EOL;
echo PHP_EOL;






/*
    
    // The above DI managed container reduces the following hand crafted code
    // that could be repeated in a large number of places.  the DI container
    // promotes the DRY principle by reducing the amount of usage or "runtime"
    // code that is needed to spin up a highly dependent object.
    
    $dbAdapter = new My\DbAdapter('foo', 'bar');
    $mapper = new My\Mapper($dbAdapter);
    $repoA = new My\RepositoryA($mapper);
    $repoB = new My\RepositoryB($mapper);

    // Replaces this hand drafted class
    class My\Service
    {
        protected $mapper = null;
        public function __construct($config)
        {
            $dbAdapter = new My\DbAdapter($config->username, $config->password);
            $this->mapper = new My\Mapper($dbAdapter);
        }
        public function getRepositoryA()
        {
            static $repo = null;
            if (!$repo) {
                $repo = new My\RepositoryA($this->mapper);
            }
            return $repo;
        }
        public function getRepositoryB()
        {
            static $repo = null;
            if (!$repo) {
                $repo = new My\RepositoryB($this->mapper);
            }
            return $repo;
        }
    }

*/



