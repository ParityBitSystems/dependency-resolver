<?php

namespace ParityBit\DependencyResolver;

use ParityBit\DependencyResolver\Exceptions\DependentClassNotFound;

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
        try {
            $reflected = new \ReflectionClass($className);
        } catch (\Exception $e) {
            // TODO: check if its always ReflectionException thrown when
            // the class isn't found and account for that instead
            throw new DependentClassNotFound('Dependent class ' . $className . ' not found', 0, $e);
        }

        $constructor = $reflected->getConstructor();

        $dependencies = $this->getDependenciesFromReflectionMethod($constructor);

        try {
            return $reflected->newInstanceArgs($dependencies);
        } catch (\Exception $e) {
            throw $e;
        }

    }
}
