<?php

namespace ParityBit\DependencyResolver\DependencyConfigurations;

class CallableResolution implements Resolution
{
    protected $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function getDependency()
    {
        return call_user_func($this->callable);
    }
}
