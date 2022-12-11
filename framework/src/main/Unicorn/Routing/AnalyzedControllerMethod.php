<?php

namespace Unicorn\Routing;

use ReflectionMethod;
use Unicorn\Http\Response;

class AnalyzedControllerMethod {
    /** @param ControllerMethodParameter[] $parameters */
    public function __construct(
        private readonly ReflectionMethod $method,
        private readonly string $httpMethod,
        private readonly string $url,
        private readonly array $parameters
    ) {
    }

    public function getName(): string {
        return $this->method->getName();
    }

    public function matches(string $httpMethod, string $url): ?array {
        if ($this->httpMethod == $httpMethod) {
            if (str_contains($this->url, '{')) {
                $matcher = '#^' . preg_replace('/{(.+?)}/', '(?<$1>.+?)', $this->url) . '$#';
                if (preg_match($matcher, $url, $matches)) {
                    return array_filter($matches, fn($key) => is_string($key), ARRAY_FILTER_USE_KEY);
                } else {
                    return null;
                }
            } else {
                return $this->url == $url ? [] : null;
            }
        } else {
            return null;
        }
    }

    public function invoke(mixed $controller, array $requestParams, array $capturedPathVariables): Response {
        if (empty($this->parameters)) {
            return $this->method->invoke($controller);
        } else {
            $arguments = array_map(fn(ControllerMethodParameter $param) => $param->resolve($requestParams, $capturedPathVariables), $this->parameters);
            return $this->method->invokeArgs($controller, $arguments);
        }
    }

    public function getUrl(array $args): ActionRoute {
        if (str_contains($this->url, '{')) {
            $replacements = [];
            foreach ($args as $name => $value) {
                $replacements['{' . $name . '}'] = $value;
            }
            return new ActionRoute(strtr($this->url, $replacements), $this->httpMethod);
        } else {
            return new ActionRoute($this->url, $this->httpMethod);
        }
    }
}