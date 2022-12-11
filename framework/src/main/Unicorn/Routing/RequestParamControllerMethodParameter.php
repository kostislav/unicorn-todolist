<?php

namespace Unicorn\Routing;

class RequestParamControllerMethodParameter implements ControllerMethodParameter {
    public function __construct(
        private readonly string $name
    ) {
    }

    public function resolve(array $requestParams, array $capturedPathVariables): mixed {
        return $requestParams[$this->name];
    }
}