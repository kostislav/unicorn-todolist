<?php

namespace Unicorn\Routing;

use InvalidArgumentException;
use ReflectionClass;
use Unicorn\Container\Container;
use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Exception\NotFoundException;
use Unicorn\Http\Response;

class Router implements ReverseRouter {
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
                        $parameters = [];
                        foreach ($method->getParameters() as $parameter) {
                            if (!empty($parameter->getAttributes(RequestParam::class))) {
                                $parameters[] = new RequestParamControllerMethodParameter($parameter->name);
                            } else if (!empty($parameter->getAttributes(UrlParam::class))) {
                                $parameters[] = new UrlParamControllerMethodParameter($parameter->name);
                            } else {
                                throw new InvalidArgumentException("Unannotated parameter {$parameter->name} on {$method}");
                            }
                        }
                        $methods[] = new AnalyzedControllerMethod($method, $httpMethod, $attributeInstance->getUrl(), $parameters);
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
        foreach ($this->routes as $baseUrl => $analyzedController) {
            if ($url == $baseUrl || str_starts_with($url, $baseUrl)) {
                $restOfUrl = self::prependSlashIfMissing(substr($url, strlen($baseUrl)));
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

    private static function prependSlashIfMissing(string $input): string {
        if ($input[0] == '/') {
            return $input;
        } else {
            return '/' . $input;
        }
    }

    public function findAction(string $controllerComponentName, string $actionName, array $args = []): ActionRoute {
        foreach ($this->routes as $baseUrl => $analyzedController) {
            if ($analyzedController->componentName == $controllerComponentName) {
                return $analyzedController->findUrl($actionName, $args)->prefixedWith($baseUrl);
            }
        }
        throw new InvalidArgumentException("No route for {$controllerComponentName}.{$actionName}");
    }
}