<?php

namespace Unicorn\Routing;

use Unicorn\Container\Container;
use Unicorn\Http\Response;

class RouteMatch {
    public function __construct(
        public readonly string $controllerName,
        public readonly string $controllerDir,
        public readonly array $capturedPathVariables,
        private readonly AnalyzedControllerMethod $controllerMethod
    ) {
    }

    public function invoke(Container $container, array $requestParams): Response {
        return $this->controllerMethod->invoke($container->get($this->controllerName), $requestParams, $this->capturedPathVariables);
    }
}