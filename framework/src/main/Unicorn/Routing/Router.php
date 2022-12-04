<?php

namespace Unicorn\Routing;

use ReflectionClass;
use Unicorn\Container\Container;
use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Exception\NotFoundException;
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

    /**
     * @throws HttpException
     */
    public function handle(string $method, string $url): RouteMatch {
        $url = self::appendSlashIfMissing($url);
        foreach ($this->routes as $baseUrl => $controllerName) {
            if ($url == $baseUrl || str_starts_with($url, $baseUrl)) {
                $restOfUrl = self::appendSlashIfMissing(substr($url, strlen($baseUrl)));
                $controllerClass = new ReflectionClass($this->container->getComponentType($controllerName));
                if ($method == 'GET') {
                    foreach ($controllerClass->getMethods() as $method) {
                        $getAttribute = $method->getAttributes(GET::class);
                        if (!empty($getAttribute)) {
                            // TODO path variables
                            if ($getAttribute[0]->newInstance()->url == $restOfUrl) {
                                // TODO params
                                return new RouteMatch($controllerName, $method);
                            }
                        }
                    }
                }
                // TODO the rest
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