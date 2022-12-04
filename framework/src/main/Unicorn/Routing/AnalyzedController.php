<?php

namespace Unicorn\Routing;

use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Exception\NotFoundException;

class AnalyzedController {
    public function __construct(
        private readonly string $componentName,
        private readonly string $controllerDir,
        /** @var AnalyzedControllerMethod[] */
        private readonly array $methods
    ) {
    }

    /**
     * @throws HttpException
     */
    public function match(string $httpMethod, string $url): RouteMatch {
        foreach ($this->methods as $method) {
            if ($method->matches($httpMethod, $url)) {
                return new RouteMatch(
                    $this->componentName,
                    $this->controllerDir,
                    $method->method
                );
            }
        }
        throw new NotFoundException();
    }
}