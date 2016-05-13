<?php

namespace ParityBit\DependencyResolver;

class HasMethodDependencies
{
    public function someMethod(ExampleDependency $dependency)
    {

    }

    public function exceptionOnInvokation()
    {
        throw new InvokationRelatedException();
    }
}
