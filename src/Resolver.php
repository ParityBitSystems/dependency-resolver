<?php

namespace ParityBit\DependencyResolver;

abstract class Resolver
{
    protected $map;

    public function __construct(DependencyMap $map)
    {
        $this->map = $map;
    }
}
