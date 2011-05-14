<?php

namespace My;

interface MapperAwareInterface
{
    public function setMapper(Mapper $mapper);
}