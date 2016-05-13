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

    public function testResolveFromConstructorArguments()
    {
        $dependency = new ExampleDependency();
        $dependency->data = ['some', 'data'];

        $dependencyMap = $this->getMockBuilder(DependencyMap::class)
                              ->setMethods(['hasConfiguration', 'resolveDependency'])
                              ->getMock();

        $dependencyMap->expects($this->once())
                      ->method('hasConfiguration')
                      ->with($this->equalTo(ExampleDependency::class))
                      ->will($this->returnValue(true));

        $dependencyMap->expects($this->once())
                    ->method('resolveDependency')
                    ->with($this->equalTo(ExampleDependency::class))
                    ->will($this->returnValue($dependency));

        $resolver = new ConstructorArgumentResolver($dependencyMap);
        $resolved = $resolver->resolveFromConstructorArguments(HasConstructorDependencies::class);

        $this->assertInstanceOf(
            HasConstructorDependencies::class,
            $resolved
        );

        $this->assertEquals($dependency, $resolved->getDependency());
    }

    public function testThrowsCaughtExceptionOnInvokation()
    {
        // This exception is a test one, to test when invokation throws an
        // exception the exception is re-thrown
        $this->setExpectedException(InvokationRelatedException::class);
        $this->resolver->resolveFromConstructorArguments(ExceptionTriggeringConstructor::class);
    }
}
