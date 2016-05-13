<?php

namespace ParityBit\DependencyResolver;

class HasConstructorDependencies
{
    protected $dependency;

    public function __construct(ExampleDependency $dependency)
    {
        $this->dependency = $dependency;
    }

    public function getDependency()
    {
        return $this->dependency;
    }
}
