<?php

namespace Unicorn\Routing;

use InvalidArgumentException;
use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Exception\NotFoundException;

class AnalyzedController {
    /** @param  AnalyzedControllerMethod[] $methods */
    public function __construct(
        public readonly string $componentName,
        private readonly string $controllerDir,
        private readonly array $methods
    ) {
    }

    /**
     * @throws HttpException
     */
    public function match(string $httpMethod, string $url): RouteMatch {
        foreach ($this->methods as $method) {
            $capturedPathVariables = $method->matches($httpMethod, $url);
            if ($capturedPathVariables !== null) {
                return new RouteMatch(
                    $this->componentName,
                    $this->controllerDir,
                    $capturedPathVariables,
                    $method
                );
            }
        }
        throw new NotFoundException();
    }

    public function findUrl(string $methodName, array $args): ActionRoute {
        foreach ($this->methods as $method) {
            if ($method->getName() == $methodName) {
                return $method->getUrl($args);
            }
        }
        throw new InvalidArgumentException("No method with name " . $methodName . ' in ' . $this->componentName);
    }
}