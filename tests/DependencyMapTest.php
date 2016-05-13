<?php

namespace ParityBit\DependencyResolver;

use ParityBit\DependencyResolver\DependencyConfigurations\Resolution;
use ParityBit\DependencyResolver\Exceptions\DependencyNotFound;

class DependencyMapTest extends \PHPUnit_Framework_TestCase
{
    protected $map;

    public function setUp()
    {
        $this->map = new DependencyMap();
    }

    public function testHasConfiguration()
    {
        $this->assertFalse($this->map->hasConfiguration(ExampleDependency::class));
        $this->assertFalse($this->map->hasConfiguration('Something'));

        $resolution = $this->getMock(Resolution::class);
        $this->map->registerResolution(ExampleDependency::class, $resolution);

        $this->assertTrue($this->map->hasConfiguration(ExampleDependency::class));

        $this->assertFalse($this->map->hasConfiguration('Something'));
    }

    public function testResolveDependencyThrowsExceptionWhenNotFound()
    {
        $this->setExpectedException(DependencyNotFound::class);
        $this->map->resolveDependency(ExampleDependency::class);
    }

    public function testResolvingDependencies()
    {
        $dependency = new ExampleDependency();
        $dependency->data = ['some', 'test', 'data'];

        $resolution = $this->getMockBuilder(Resolution::class)
                            ->setMethods(['getDependency'])
                            ->getMock();

        $resolution->expects($this->once())
                   ->method('getDependency')
                   ->will($this->returnValue($dependency));

        $this->map->registerResolution(ExampleDependency::class, $resolution);

        $this->assertEquals(
            $dependency,
            $this->map->resolveDependency(ExampleDependency::class)
        );
    }
}
