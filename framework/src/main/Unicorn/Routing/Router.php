<?php

namespace Unicorn\Routing;

use ReflectionClass;
use Unicorn\Container\Container;
use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Exception\NotFoundException;
use Unicorn\Http\Response;

class Router {
    /** @param AnalyzedController[] $routes */
    private function __construct(
        private readonly array $routes
    ) {
    }

    public static function create(Container $container): self {
        $routes = [];
        foreach ($container->getComponentsWithAttribute(Controller::class) as $controllerName => $attributes) {
            $baseUrl = $attributes[0]->newInstance()->baseUrl;
            $controllerClass = new ReflectionClass($container->getComponentType($controllerName));
            $methods = [];
            foreach ($controllerClass->getMethods() as $method) {
                foreach ($method->getAttributes() as $attribute) {
                    $attributeType = new ReflectionClass($attribute->getName());
                    if ($attributeType->implementsInterface(RouteAttribute::class)) {
                        $attributeInstance = $attribute->newInstance();
                        $httpMethod = $attributeInstance->getMethod();
                        $methods[] = new AnalyzedControllerMethod($method, $httpMethod, $attributeInstance->getUrl());
                    }
                }
            }

            $routes[self::appendSlashIfMissing($baseUrl)] = new AnalyzedController(
                $controllerName,
                dirname($controllerClass->getFileName()),
                $methods
            );
        }

        uksort($routes, fn($a, $b) => strlen($b) - strlen($a)); // longest first
        return new self(
            $routes
        );
    }

    /**
     * @throws HttpException
     */
    public function handle(string $method, string $url): RouteMatch {
        $url = self::appendSlashIfMissing($url);
        foreach ($this->routes as $baseUrl => $analyzedController) {
            if ($url == $baseUrl || str_starts_with($url, $baseUrl)) {
                $restOfUrl = self::appendSlashIfMissing(substr($url, strlen($baseUrl)));
                return $analyzedController->match($method, $restOfUrl);
            }
        }
        throw new NotFoundException();
    }

    private static function appendSlashIfMissing(string $input): string {
        if ($input[strlen($input) - 1] == '/') {
            return $input;
        } else {
            return $input . '/';
        }
    }
}