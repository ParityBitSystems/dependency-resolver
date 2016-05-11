<?php

namespace ParityBit\DependencyResolver;

abstract class ReflectionMethodResolver extends Resolver
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
}
