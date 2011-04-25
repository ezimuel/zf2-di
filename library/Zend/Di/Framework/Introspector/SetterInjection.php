<?php

namespace Zend\Di\Framework\Introspector;

class SetterInjection implements \Zend\Di\Framework\Introspector
{
    protected $configuration = null;
    
    /**
     * @var Zend\Di\Framework\ManagedDefinitions
     */
    protected $managedDefinitions = null;
    protected $typeRegistry = null;
    
    public function setConfiguration(array $configuration)
    {
        $this->configuration = $configuration;
    }
    
    public function setManagedDefinitions(\Zend\Di\Framework\ManagedDefinitions $managedDefinitions)
    {
        $this->managedDefinitions = $managedDefinitions;
    }
    
    public function setTypeRegistry(\Zend\Di\Framework\TypeRegistry $classRegistry)
    {
        $this->typeRegistry = $classRegistry;
    }
    
    public function introspect()
    {
        foreach ($this->typeRegistry as $type) {

            try {
                $refClass = new \ReflectionClass($type);
                echo 'Reflecting ' . $type . PHP_EOL;
                
                foreach ($refClass->getMethods() as $refMethod) {
                    if (preg_match('#^set.*#', $refMethod->getName())) {
                        echo 'Found injectable method: ' . $refMethod->getName() . PHP_EOL;
                        
                        if ($this->managedDefinitions->hasDefinition($type)) {
                            $definition = $this->managedDefinitions->getDefinition($type);
                            
                            
                            // resolve what happens if the method already exists
                            
//                            $methodCalls = $definition->getMethodCalls();
//                            $foundMethod = false;
//                            foreach ($methodCalls as $methodCall) {
//                                if ($methodCalls->key() === $refMethod->getName()) {
//                                    $foundMethod = $methodCall;
//                                }
//                            }
//                            unset($methodCalls);
//                            if ($foundMethod) {
//                                $method = $foundMethod;
//                            } else {
//                                $method = new \Zend\Di\Method($name, $args)
//                            }

                        } else {
                            $definition = new \Zend\Di\Framework\DefinitionProxy(new \Zend\Di\Definition($type));
                            $this->managedDefinitions->addDefinition($definition);
                        }
                        
                        if ($refParameters = $refMethod->getParameters()) {
                            $args = array();
                            foreach ($refParameters as $refParam) {
                                $paramMaps[$refParam->getName()] = $refParam->getPosition();
                                
                                if ($refTypeClass = $refParam->getClass()) {
                                                                
                                    echo '      Param type: ' . $refTypeClass->getName() . PHP_EOL;
                                    $args[] = new \Zend\Di\Reference($refTypeClass->getName());
                                }
                            }
                            $definition->addMethodCall($refMethod->getName(), $args);
                        }
                    }
                }
                
            } catch (\ReflectionException $e) {
                throw new \Exception('An unmanaged type was found as a dependency');
            }
                
        }
    }

}
