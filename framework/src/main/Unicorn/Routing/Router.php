<?php

namespace Unicorn\Routing;

use ReflectionClass;
use Unicorn\Container\Container;
use Unicorn\Http\Response;

class Router {
    private function __construct(
        private readonly Container $container,
        private readonly array $routes
    ) {
    }

    public static function create(Container $container): self {
        $routes = [];
        foreach ($container->getComponentsWithAttribute(Controller::class) as $controllerName => $attributes) {
            $baseUrl = $attributes[0]->newInstance()->baseUrl;
            $routes[self::appendSlashIfMissing($baseUrl)] = $controllerName;
        }
        uksort($routes, fn($a, $b) => strlen($b) - strlen($a)); // longest first
        return new self(
            $container,
            $routes
        );
    }

    public function handle(string $method, string $url): Response {
        $url = self::appendSlashIfMissing($url);
        foreach ($this->routes as $baseUrl => $controllerName) {
            if ($url == $baseUrl || str_starts_with($url, $baseUrl)) {
                $restOfUrl = self::appendSlashIfMissing(substr($url, strlen($baseUrl)));
                $controller = $this->container->get($controllerName);
                $controllerClass = new ReflectionClass($controller);
                if ($method == 'GET') {
                    foreach ($controllerClass->getMethods() as $method) {
                        $getAttribute = $method->getAttributes(GET::class);
                        if (!empty($getAttribute)) {
                            // TODO path variables
                            if ($getAttribute[0]->newInstance()->url == $restOfUrl) {
                                // TODO params
                                return $method->invoke($controller);
                            }
                        }
                    }
                }
                // TODO the rest
            }
        }
        // TODO 404
    }

    private static function appendSlashIfMissing(string $input): string {
        if ($input[strlen($input) - 1] == '/') {
            return $input;
        } else {
            return $input . '/';
        }
    }
}