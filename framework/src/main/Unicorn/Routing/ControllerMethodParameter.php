<?php

namespace Unicorn\Routing;

interface ControllerMethodParameter {
    public function resolve(array $requestParams, array $capturedPathVariables): mixed;
}