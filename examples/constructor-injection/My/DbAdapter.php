<?php

namespace My;

class DbAdapter
{
    protected $username = null;
    protected $password = null;
    
    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        if ($username == null || $password == null) {
            throw new \Exception('Username or password was no supplied');
        }
    }
    
    public function __toString()
    {
        return 'I am ' . get_class($this) . ' object (hash ' . spl_object_hash($this) . '), with these parameters (username = ' . $this->username . ', password = ' . $this->password . ')';
    }
    
}