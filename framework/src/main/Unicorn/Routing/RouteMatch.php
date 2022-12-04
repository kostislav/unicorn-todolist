<?php

namespace Unicorn\Routing;

use ReflectionMethod;
use Unicorn\Container\Container;
use Unicorn\Http\Response;

class RouteMatch {
    public function __construct(
        private readonly string $controllerName,
        public readonly string $controllerDir,
        private readonly ReflectionMethod $controllerMethod
    ) {
    }

    public function invoke(Container $container): Response {
        return $this->controllerMethod->invoke($container->get($this->controllerName));
    }
}