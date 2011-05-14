<?php return array (
  0 => 
  array (
    'class' => 'My\\DbAdapter',
    'methods' => 
    array (
    ),
    'param_map' => 
    array (
      'username' => 0,
      'password' => 1,
    ),
    'params' => 
    array (
      'username' => 'my-u',
      'password' => 'my-p',
    ),
  ),
  1 => 
  array (
    'class' => 'My\\Mapper',
    'methods' => 
    array (
      0 => 
      array (
        'name' => 'setDbAdapter',
        'args' => 
        array (
          0 => 
          array (
            '__reference' => 'My\\DbAdapter',
          ),
        ),
      ),
    ),
    'param_map' => 
    array (
    ),
    'params' => 
    array (
    ),
  ),
  2 => 
  array (
    'class' => 'My\\RepositoryA',
    'methods' => 
    array (
      0 => 
      array (
        'name' => 'setMapper',
        'args' => 
        array (
          0 => 
          array (
            '__reference' => 'My\\Mapper',
          ),
        ),
      ),
    ),
    'param_map' => 
    array (
    ),
    'params' => 
    array (
    ),
  ),
  3 => 
  array (
    'class' => 'My\\RepositoryB',
    'methods' => 
    array (
      0 => 
      array (
        'name' => 'setMapper',
        'args' => 
        array (
          0 => 
          array (
            '__reference' => 'My\\Mapper',
          ),
        ),
      ),
    ),
    'param_map' => 
    array (
    ),
    'params' => 
    array (
    ),
  ),
);