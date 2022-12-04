<?php

namespace Unicorn\Container;

use ReflectionClass;
use Symfony\Component\Yaml\Yaml;

class YamlConfigFileReader {
    public function parse(string $filename, string $targetType): mixed {
        $yaml = Yaml::parseFile($filename);
        $targetClass = new ReflectionClass($targetType);
        $constructor = $targetClass->getConstructor();
        $parameters = [];
        foreach ($constructor->getParameters() as $parameter) {
            $parameters[] = $yaml[$parameter->getName()];
        }
        return $targetClass->newInstanceArgs($parameters);
    }
}