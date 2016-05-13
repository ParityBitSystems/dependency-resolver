# Simple dependency resolver

[![Coverage Status](https://coveralls.io/repos/github/ParityBitSystems/dependency-resolver/badge.svg?branch=master)](https://coveralls.io/github/ParityBitSystems/dependency-resolver?branch=master) [![Build Status](https://travis-ci.org/ParityBitSystems/dependency-resolver.svg?branch=master)](https://travis-ci.org/ParityBitSystems/dependency-resolver)

A simple framework for resolving dependencies in code and managing a mapping of
configurations to determine how specific dependencies should be resolved.

This isn't optimal and there are likely better implementations out there, but
I needed something very quickly for a few specific, temporary, use cases. So
it may not be very useful in general.

## Installation

With composer

    php composer.phar require paritybit/dependency-resolver

## Usage

There is an example `Resolution` implementation `CallableResolution`. A `Resolution`
is the encapsulation of a particular dependency. If one of your dependencies is
an object, then you would tell the `Resolution` how to create or get this object
in order for it to be provided when something is dependent on it. In most cases
you would want to create your own `Resolution` implementation. My own use case
was to get an object from a container until the container could be refactored
away.

Create a map of your dependencies

    use ParityBit\DependencyResolver\DependencyMap;
    use ParityBit\DependencyResolver\DependencyConfigurations\CallableResolution;
    use My\Applications\Dependency;

    $map = new DependencyMap();
    $map->registerResolution(
        Dependency::class,
        new CallableResolution(function () {
            return new Dependency();
        })
    );


Instantiate the appropriate resolver

    $resolver = new ParityBit\DependencyResolver\ConstructorArgumentResolver($map);

Resolve your dependencies

    use Class\With\Dependencies\In\Constructor as ToResolve;

    // find dependencies, instantiate dependent object and inject dependencies
    $resolved = $resolver->resolve(ToResolve::class);
