<?php

namespace Unicorn;

use Throwable;
use Unicorn\Container\Container;
use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Response;
use Unicorn\Routing\Router;
use Unicorn\Template\LazyTemplateEngine;

class App {
    private function __construct(
        private readonly Container $container,
        private readonly Router $router
    ) {
    }

    public static function handleGlobalRequest(string $containerConfigClassName): void {
        try {
            $app = self::create($containerConfigClassName, realpath($_SERVER['DOCUMENT_ROOT'] . '/..'));
            $requestMethod = $_SERVER['REQUEST_METHOD'];
            $app->handleAndSend($requestMethod, $_SERVER['REQUEST_URI'], $requestMethod == 'POST' ? $_POST : $_GET);
        } catch (HttpException $e) {
            http_response_code($e->statusCode);
            echo $e->statusCode, '<br />';
            echo nl2br($e->getTraceAsString());
        } catch (Throwable $e) {
            echo $e->getMessage(), '<br/>';
            echo nl2br($e->getTraceAsString());
        }
    }

    public static function create(string $containerConfigClassName, string $baseDir): self {
        $container = Container::create($containerConfigClassName, $baseDir);
        $router = Router::create($container);
        return new self(
            $container,
            $router
        );
    }

    /**
     * @throws Http\Exception\HttpException
     */
    public function handleAndSend(string $method, string $url, array $requestParams): void {
        $routeMatch = $this->router->handle($method, $url);
        $response = $routeMatch->invoke($this->container, $requestParams);
        $response->send(
            new LazyTemplateEngine($this->container),
            $routeMatch->controllerDir
        );
    }
}