<?php

namespace Unicorn\Container;

use ReflectionClass;
use ReflectionMethod;

// TODO builder
class Container {
    /** @var ReflectionMethod[] */
    private array $componentInitializers = [];
    private array $components = [];
    private array $containerConfigInstances = [];

    public static function create(string $containerConfigClassName): self {
        $container = new self();
        $container->processConfigClass($containerConfigClassName);
        return $container;
    }

    public function get(string $name) {
        if (!array_key_exists($name, $this->components)) {
            $method = $this->componentInitializers[$name];
            $parameters = [];
            foreach ($method->getParameters() as $parameter) {
                $parameters[] = $this->get($parameter->name);
            }

            $this->components[$name] = $method->invoke($this->getContainerConfigInstance($method->getDeclaringClass()->name), ...$parameters);
        }
        return $this->components[$name];
    }

    /** @return array[string => ReflectionAttribute[]]> */
    public function getComponentsWithAttribute(string $attributeName): array {
        $result = [];
        foreach ($this->componentInitializers as $name => $method) {
            $attributes = $method->getAttributes($attributeName);
            if (!empty($attributes)) {
                $result[$name] = $attributes;
            }
        }
        return $result;
    }

    private function getContainerConfigInstance(string $containerConfigClassName) {
        if (!array_key_exists($containerConfigClassName, $this->containerConfigInstances)) {
            // TODO constructor parameters
            $this->containerConfigInstances[$containerConfigClassName] = new $containerConfigClassName();
        }
        return $this->containerConfigInstances[$containerConfigClassName];
    }

    private function processConfigClass(string $containerConfigClassName): void {
        $containerConfigClass = new ReflectionClass($containerConfigClassName);
        foreach ($containerConfigClass->getMethods() as $method) {
            $this->componentInitializers[$method->name] = $method;
        }
        // TODO check if already processed
        foreach ($containerConfigClass->getAttributes(Import::class) as $import) {
            $this->processConfigClass($import->newInstance()->containerConfigClassName);
        }
    }
}