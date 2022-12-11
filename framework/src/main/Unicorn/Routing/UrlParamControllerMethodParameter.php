<?php

namespace Unicorn\Routing;

class UrlParamControllerMethodParameter implements ControllerMethodParameter {
    public function __construct(
        private readonly string $name
    ) {
    }

    public function resolve(array $requestParams, array $capturedPathVariables): mixed {
        return $capturedPathVariables[$this->name];
    }
}