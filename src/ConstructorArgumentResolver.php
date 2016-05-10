<?php

namespace ParityBit\DependencyResolver;

class ConstructorArgumentResolver extends ReflectionMethodResolver
{
    /**
     * Resolve dependencies a class has as constructor arguments
     *
     * @param string $className
     *
     * @return object
     */
    public function resolveFromConstructorArguments($className)
    {
        $reflected = new \ReflectionClass($className);
        $constructor = $reflected->getConstructor();

        $dependencies = $this->getDependenciesFromReflectionMethod($constructor);

        try {
            return $reflected->newInstanceArgs($dependencies);
        } catch (\Exception $e) {
            throw $e;
        }

    }
}
