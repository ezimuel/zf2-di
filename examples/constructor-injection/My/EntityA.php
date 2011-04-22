<?php

namespace My;

class EntityA implements EntityInterface
{
    
    public function __construct()
    {}
    
    public function __toString()
    {
        return 'I am a ' . get_class($this) . ' object (hash ' . spl_object_hash($this) . '), using this mapper object ';
    }
    
}