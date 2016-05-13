<?php

namespace ParityBit\DependencyResolver;

class ExceptionTriggeringConstructor
{
    public function __construct()
    {
        throw new InvokationRelatedException();
    }
}
