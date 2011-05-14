<?php

namespace My;

interface DbAdapterAwareInterface
{
    public function setDbAdapter(DbAdapter $dbAdapter);
}
