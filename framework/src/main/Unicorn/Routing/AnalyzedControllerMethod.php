<?php

namespace Unicorn\Routing;

use ReflectionMethod;
use Unicorn\Http\Response;

class AnalyzedControllerMethod {
    public function __construct(
        private readonly ReflectionMethod $method,
        public readonly string $httpMethod,
        public readonly string $url,
        private readonly array $parameters
    ) {
    }

    public function getName(): string {
        return $this->method->getName();
    }

    public function matches(string $httpMethod, string $url): bool {
        return $this->httpMethod == $httpMethod && $this->url == $url;
    }

    public function invoke(mixed $controller, array $requestParams): Response {
        if (empty($this->parameters)) {
            return $this->method->invoke($controller);
        } else {
            $arguments = array_map(fn($name) => $requestParams[$name] , $this->parameters);
            return $this->method->invokeArgs($controller, $arguments);
        }
    }
}