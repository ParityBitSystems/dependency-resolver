<?php

namespace ParityBit\DependencyResolver\DependencyConfigurations;

class CallableResolutionTest extends \PHPUnit_Framework_TestCase
{
    protected $resolution;
    protected $callableResult;

    public function setUp()
    {
        $this->callableResult = new \stdClass();
        $this->callableResult->some = 'property';


        $this->resolution = new CallableResolution(function () {
            return $this->callableResult;
        });
    }

    public function testImplementsResolution()
    {
        $this->assertInstanceOf(Resolution::class, $this->resolution);
    }

    public function testGetDependency()
    {
        $this->assertEquals(
            $this->callableResult,
            $this->resolution->getDependency()
        );
    }
}
