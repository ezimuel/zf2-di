<?php

namespace Zend\Di;

use Zend\Di\Framework\ManagedDefinitions;

use Zend\Di\Framework\ClassLocator;

class Framework
{
    const CLASSNAME = __CLASS__;
    
    /**
     * @var Zend\Di\Framework\Configuration
     */
    protected $configuration = null;
    protected $di = null;

    protected $definition = null;
    
    public function __construct(Framework\Configuration $configuration = null, DependencyInjection $di = null)
    {
        $this->configuration = ($configuration) ?: new Framework\Configuration();
        $this->di = ($di) ?: new DependencyInjector();
    }
    
    public function configuration()
    {
        return $this->configuration;
    }
    
    public function di()
    {
        return $this->di;
    }
    
    protected function validateConfiguration()
    {
        if ($this->configuration->getContainerConfigurationPath() == null) {
            throw new \Exception('A containerConfigurationPath is required by ' . __CLASS__);
        }
        
        if ($this->mode == Framework\Configuration::MODE_DEVELOPMENT) {
            // @todo dev time required configuration stuffs
        }
    }
    
    protected function processConfiguration()
    {
//        if (!isset($this->frameworkConfig['classes'])) {
//            throw new \Exception('A values for classes must exist');
//        }
//        if (!isset($this->frameworkConfig['classes']['namespaces']) && !isset($this->frameworkConfig['classes']['directories'])) {
//            throw new \Exception('Either a namespace or a directory must be provided for the classes configuration');
//        }
//        
//        if (!isset($this->frameworkConfig['classes']['namespaces'])) {
//            $this->frameworkConfig['classes']['namespaces'] = array();
//        }
//        if (!isset($this->frameworkConfig['classes']['directories'])) {
//            $this->frameworkConfig['classes']['directories'] = array();
//        }

    }
    

    
    public function build()
    {
        // validate the config object ?
        $this->validateConfiguration();
        
        // process the config ?
        $this->processConfiguration();
        
        // check to see if there is a dev time file stat cache (performance during dev)
        $developmentStatFilePath = $this->configuration->getDevelopmentFileStatPath();
        
        if ($developmentStatFilePath !== null) {
            if (!file_exists($developmentStatFilePath)) {
                // if it doesnt exist, create it with empty values
                if (file_put_contents($developmentStatFilePath, '<?php return ' . var_export(array(), true) . ';') === false) {
                    throw new \Exception('Stat file not writable'); // exception if path is provided, but unwritable
                }
            }
            // @todo perhaps ensure its a php file?
            $developmentFileStatInfo = include $developmentStatFilePath; 
        }

        $typeRegistry = new Framework\TypeRegistry;
        
        // if file stat info was provided, use it
        if (isset($developmentFileStatInfo) && is_array($developmentFileStatInfo)) {
            $typeRegistry->setFileStatInformation($developmentFileStatInfo);
        }
        
        // create class manager instance, with provided namespaces and directories to manage
        $classManager = new Framework\TypeManager(
            $typeRegistry,
            $this->configuration->getManagedNamespaces(),
            $this->configuration->getManagedDirectories()
        );
        
        $classManager->manage();
        
        if ($developmentStatFilePath !== null && $typeRegistry->hasFileStatUpdates()) {
            file_put_contents($developmentStatFilePath, '<?php return ' . var_export($typeRegistry->getFileStatInformation(), true) . ';');
        }
        
        // load the managed di definitions
        $managedDefinitions = new ManagedDefinitions();
        $containerConfigPath = $this->configuration->getContainerConfigurationPath();
        if ($containerConfigPath == null) {
            throw new \Exception('A container configuration path was not found.');
        }
        if (!file_exists($containerConfigPath)) {
            if (file_put_contents($containerConfigPath, '<?php return ' . var_export(array(), true) . ';') === false) {
                throw new \Exception('Container configuration file not writable'); // exception if path is provided, but unwritable
            }
            $containerConfigArray = include $containerConfigPath;
            if ($containerConfigArray) {
                $managedDefinitions->addDefinitionFromArray($containerConfigArray);
            }
        }
        
        foreach ($this->configuration->getIntrospectors() as $introspectorName) {
            $introspectorClass = 'Zend\Di\Framework\Introspector\\' . ucfirst($introspectorName);
            /* @var Zend\Di\Framework\Introspector $introspector */
            $introspector = new $introspectorClass;
            $introspector->setConfiguration($this->configuration->getIntrospectionConfiguration($introspectorName));
            $introspector->setManagedDefinitions($managedDefinitions);
            $introspector->setTypeRegistry($typeRegistry);
            $introspector->introspect();           
        }
        
        $managedDefinitions->mergeObjectConfiguration($this->configuration->getObjectConfigurations());
        
        file_put_contents($containerConfigPath, '<?php return ' . var_export($managedDefinitions->toArray(), true) . ';');
        
    }
    
    public function bootstrap()
    {
        $this->validateConfiguration();
        
        $configuration = new Configuration($this->di);
        
        if (file_exists($this->frameworkConfig['containerConfigurationPath'])) {
            $containerConfiguration = include $this->frameworkConfig['containerConfigurationPath'];
            $configuration->fromArray($containerConfiguration);
        }
        
        $mode = $this->configuration->getMode();
        
        if ($mode == Framework\Configuration::MODE_PRODUCTION) {
            //if (isset($this->config['definitionPath']) && file_exists($this->config[''])))
        }
        
        if ($mode == Framework\Configuration::MODE_DEVELOPMENT) {
            $this->build();
        }
    }
    
}