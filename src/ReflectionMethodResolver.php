<?php

namespace ParityBit\DependencyResolver;

abstract class ReflectionMethodResolver extends Resolver
{
    protected function getDependenciesFromReflectionMethod(\ReflectionMethod $method)
    {
        $dependencies = [];

        $method = $reflected->getConstructor();

        foreach ($method->getParameters() as $parameter) {

            $default = $parameter->getDefaultValue();

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

            $dependencies[] = $default;
        }
    }
}
