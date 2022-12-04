<?php

namespace Unicorn\Container;

use Attribute;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionMethod;

// TODO builder
class Container {
    /** @var ReflectionMethod[] */
    private array $componentInitializers = [];
    private array $components = [];
    private array $containerConfigInstances = [];
    private YamlConfigFileReader $yamlConfigFileReader;

    private function __construct(
        private string $baseDir
    ) {
        $this->yamlConfigFileReader = new YamlConfigFileReader();
    }

    public static function create(string $containerConfigClassName, string $baseDir): self {
        $container = new self($baseDir);
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

    public function getComponentType(string $name): string {
        return $this->componentInitializers[$name]->getReturnType()->getName();
    }

    private function getContainerConfigInstance(string $containerConfigClassName) {
        if (!array_key_exists($containerConfigClassName, $this->containerConfigInstances)) {
            $containerConfigClass = new ReflectionClass($containerConfigClassName);
            $constructor = $containerConfigClass->getConstructor();
            if ($constructor != null) {
                $parameters = [];
                foreach ($constructor->getParameters() as $parameter) {
                    $configFileAttribute = $parameter->getAttributes(ConfigFile::class);
                    if (!empty($configFileAttribute)) {
                        $parameters[] = $this->yamlConfigFileReader->parse($this->baseDir . '/config/' . $configFileAttribute[0]->newInstance()->relativePath, $parameter->getType());
                    } else {
                        throw new InvalidArgumentException("No #[ConfigFile] attribute found on constructor parameter of " . $containerConfigClassName);
                    }
                    return $containerConfigClass->newInstanceArgs($parameters);
                }
            } else {
                $this->containerConfigInstances[$containerConfigClassName] = new $containerConfigClassName();
            }
        }
        return $this->containerConfigInstances[$containerConfigClassName];
    }

    private function processConfigClass(string $containerConfigClassName): void {
        $containerConfigClass = new ReflectionClass($containerConfigClassName);
        foreach ($containerConfigClass->getMethods() as $method) {
            $this->componentInitializers[$method->name] = $method;
        }
        self::processImports($containerConfigClass);
    }

    // TODO check if already processed
    private function processImports(ReflectionClass $configClass) {
        foreach ($configClass->getAttributes() as $attribute) {
            $attributeType = $attribute->getName();
            if ($attributeType == Import::class) {
                foreach ($attribute->newInstance()->containerConfigClassNames as $className) {
                    $this->processConfigClass($className);
                }
            } else if ($attributeType != Attribute::class) {
                self::processImports(new ReflectionClass($attributeType));
            }
        }
    }
}