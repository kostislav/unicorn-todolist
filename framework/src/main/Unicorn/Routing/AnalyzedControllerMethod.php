<?php

namespace Unicorn\Routing;

use ReflectionMethod;
use Unicorn\Http\Response;

class AnalyzedControllerMethod {
    public function __construct(
        private readonly ReflectionMethod $method,
        private readonly string $httpMethod,
        private readonly string $url
    ) {
    }

    public function matches(string $httpMethod, string $url): bool {
        return $this->httpMethod == $httpMethod && $this->url == $url;
    }

    public function invoke(mixed $controller): Response {
        return $this->method->invoke($controller);
    }
}