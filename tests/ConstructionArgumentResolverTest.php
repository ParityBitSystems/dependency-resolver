<?php

namespace ParityBit\DependencyResolver;

use ParityBit\DependencyResolver\Exceptions\DependentClassNotFound;

class ConstructorArgumentResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;

    public function setUp()
    {
        $dependencyMap = new DependencyMap();
        $this->resolver = new ConstructorArgumentResolver($dependencyMap);
    }

    public function testInvalidDependentThrowsException()
    {
        $this->setExpectedException(DependentClassNotFound::class);
        $this->resolver->resolveFromConstructorArguments('Some\Fake\Class');
    }
}
