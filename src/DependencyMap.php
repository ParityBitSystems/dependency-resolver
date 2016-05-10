<?php

namespace ParityBit\DependencyResolver;

use ParityBit\DependencyResolver\DependencyConfigurations\Resolution;

/**
 * A dependency resolution map
 * maps known dependency names with dependency resolution configurations
 * and provides an interface to obtain a dependency from a name
 */
class DependencyMap
{
    protected $container;
    protected $mapping = [];

    /**
     * Register a new dependency configuration resolution in the map
     *
     * @param Resolution
     */
    public function registerResolution($dependencyName, Resolution $resolution)
    {
        $this->mapping[$dependencyName] = $resolution;
    }

    /**
     * Does the mapper have a dependency configuration for
     * a given dependency name
     *
     * @param string $dependencyName
     *
     * @return bool
     */
    public function hasConfiguration($dependencyName)
    {
        return (array_key_exists($dependencyName, $this->mapping));
    }

    /**
     * Resolve a dependency from a given name
     * this involves finding the correct resolution
     *
     * @param string $dependencyName
     *
     * @return mixed
     */
    public function resolveDependency($dependencyName)
    {
        if (!array_key_exists($dependencyName, $this->mapping)) {
            throw new \Exception('Dependency Not Found');
        }

        return $this->mapping[$dependencyName]->getDependency();
    }
}
