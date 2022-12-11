<?php

namespace Unicorn\Routing;

use ReflectionMethod;
use Unicorn\Container\Container;
use Unicorn\Http\Response;

class RouteMatch {
    public function __construct(
        private readonly string $controllerName,
        public readonly string $controllerDir,
        private readonly AnalyzedControllerMethod $controllerMethod
    ) {
    }

    public function invoke(Container $container, array $requestParams): Response {
        return $this->controllerMethod->invoke($container->get($this->controllerName), $requestParams);
    }
}