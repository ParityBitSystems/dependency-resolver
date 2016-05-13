<?php

namespace ParityBit\DependencyResolver;

use ParityBit\DependencyResolver\Exceptions\DependentMethodNotFound;

class ReflectionMethodResolver extends Resolver
{
    protected function getDependenciesFromReflectionMethod(\ReflectionMethod $method)
    {
        $dependencies = [];

        foreach ($method->getParameters() as $parameter) {

            $hint = $parameter->getClass();
            if (!is_null($hint)) {
                if ($this->map->hasConfiguration($hint->getName())) {
                    $dependencies[] = $this->map->resolveDependency($hint->getName());

                    continue;
                }
            }

            if ($this->map->hasConfiguration($parameter->getName())) {
                $dependencies[] = $this->map->resolveDependency($parameter->getName());

                continue;
            }

            if (!$parameter->isOptional()) {
                throw new \LogicException('No configuration provided for a non-optional parameter');
            }

            $dependencies[] = $parameter->getDefaultValue();
        }

        return $dependencies;
    }

    public function resolveFromObjectAndMethod($object, $methodName)
    {
        if (!is_object($object)) {
            throw new \LogicException('Object to resolve dependencies for is not an object');
        }

        try {
            $reflected = new \ReflectionMethod($object, $methodName);
        } catch (\Exception $e) {
            // TODO: check if its always ReflectionException thrown when
            // the method isn't found and account for that instead
            throw new DependentMethodNotFound('Dependent method ' . $methodName . ' not found in ' . get_class($object), 0, $e);
        }

        $dependencies = $this->getDependenciesFromReflectionMethod($reflected);

        try {
            return call_user_func_array([$object, $methodName], $dependencies);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
