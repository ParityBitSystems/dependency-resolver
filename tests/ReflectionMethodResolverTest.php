<?php

namespace ParityBit\DependencyResolver;

use ParityBit\DependencyResolver\Exceptions\DependentClassNotFound;

class ReflectionMethodResolverTest extends \PHPUnit_Framework_TestCase
{
    protected $resolver;
    protected $dependency;

    public function setUp()
    {
        $dependencyMap = new DependencyMap();
        $this->resolver = new ReflectionMethodResolver($dependencyMap);
    }

    public function testResolveFromObjectAndMethodFailsWithNoObject()
    {
        $this->setExpectedException('LogicException');
        $this->resolver->resolveFromObjectAndMethod('notAnObject', 'methodName');
    }

    public function testTypeHintedDependencies()
    {
        $this->dependency = new ExampleDependency();
        $this->dependency->data = ['some', 'data'];

        $method = new \ReflectionMethod($this, 'typeHintedMethod');
        $dependencies = $this->getDependencies($method, 'type-hinted');

        $this->assertCount(1, $dependencies);

        if (1 == count($dependencies)) {
            $this->assertEquals($this->dependency, $dependencies[0]);
        }
    }

    public function typeHintedMethod(ExampleDependency $dependency)
    {

    }

    public function testNonTypeHintedDependencies()
    {
        $this->dependency = new \stdClass;
        $this->dependency->data = ['some', 'data', 'no-type-hint-provided'];

        $method = new \ReflectionMethod($this, 'nonTypeHintedDependencies');
        $dependencies = $this->getDependencies($method, 'no-type-hint');

        $this->assertCount(1, $dependencies);

        if (1 == count($dependencies)) {
            $this->assertEquals($this->dependency, $dependencies[0]);
        }
    }

    public function nonTypeHintedDependencies($someDependency)
    {

    }

    public function testMissingConfigurationForNonOptionalDependency()
    {
        $method = new \ReflectionMethod($this, 'nonTypeHintedNonConfiguredDependencies');

        $this->setExpectedException('LogicException');
        $dependencies = $this->getDependencies($method);
    }

    public function nonTypeHintedNonConfiguredDependencies($otherDependency)
    {

    }

    public function testMissingConfigurationForOptionalDependency()
    {
        $method = new \ReflectionMethod($this, 'nonTypeHintedNonConfiguredDependenciesWithDefault');

        $dependencies = $this->getDependencies($method);

        $this->assertCount(1, $dependencies);

        if (1 == count($dependencies)) {
            $this->assertEquals('default_value', $dependencies[0]);
        }
    }

    public function nonTypeHintedNonConfiguredDependenciesWithDefault($otherDependency = 'default_value')
    {

    }

    protected function getDependencies($methodWithDependencies, $type = null)
    {
        $dependencyMap = $this->getMockBuilder(DependencyMap::class)
                              ->setMethods(['hasConfiguration', 'resolveDependency'])
                              ->getMock();

        if ($type == 'type-hinted') {
            $dependencyMap->expects($this->once())
                          ->method('hasConfiguration')
                          ->with($this->equalTo(ExampleDependency::class))
                          ->will($this->returnValue(true));

            $dependencyMap->expects($this->once())
                        ->method('resolveDependency')
                        ->with($this->equalTo(ExampleDependency::class))
                        ->will($this->returnValue($this->dependency));
        } elseif ($type == 'no-type-hint') {
            $dependencyMap->expects($this->once())
                          ->method('hasConfiguration')
                          ->with($this->equalTo('someDependency'))
                          ->will($this->returnValue(true));

            $dependencyMap->expects($this->once())
                        ->method('resolveDependency')
                        ->with($this->equalTo('someDependency'))
                        ->will($this->returnValue($this->dependency));
        } else {
            $dependencyMap->expects($this->once())
                          ->method('hasConfiguration')
                          ->with($this->equalTo('otherDependency'))
                          ->will($this->returnValue(false));
        }




        $resolver = new ReflectionMethodResolver($dependencyMap);

        $method = new \ReflectionMethod($resolver, 'getDependenciesFromReflectionMethod');
        $method->setAccessible(true);

        try {
            return $method->invoke($resolver, $methodWithDependencies);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
