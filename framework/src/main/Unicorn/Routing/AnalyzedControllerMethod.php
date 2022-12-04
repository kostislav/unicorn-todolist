<?php

namespace Unicorn\Routing;

use ReflectionMethod;

class AnalyzedControllerMethod {
    public function __construct(
        public readonly ReflectionMethod $method,
        private readonly string $httpMethod,
        private readonly string $url
    ) {
    }

    public function matches(string $httpMethod, string $url): bool {
        return $this->httpMethod == $httpMethod && $this->url == $url;
    }
}