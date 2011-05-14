<?php

return array(
    // container configuration path (managed)
	'containerConfigurationPath' => __DIR__ . '/managed-di-container-config.php',
    
    // stat file for development (managed), not yet implemented
    //'developmentFileStatPath' => __DIR__ . '/managed-di-stat-file.php',
    
    // what classes and files to manage, 'manageDirectories' is also an option
    'managedNamespaces' => array('My'),
    
    // what inspectors to use
    'introspectors' => array(
        'constructorInjection',
        'interfaceInjection' => array(
            'My\DbAdapterAwareInterface',
            'My\MapperAwareInterface'
            )
        ),

    // what object configurations and aliases to use
    'objectConfigurations' => array(
        'My\DbAdapter' => array('username' => 'my-u', 'password' => 'my-p')
        )
);
