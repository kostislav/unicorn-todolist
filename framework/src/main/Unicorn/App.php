<?php

namespace Unicorn;

use Throwable;
use Unicorn\Container\Container;
use Unicorn\Http\Exception\HttpException;
use Unicorn\Http\Response;
use Unicorn\Routing\Router;

class App {
    private function __construct(
        private readonly Router $router
    ) {
    }

    public static function handleGlobalRequest(string $containerConfigClassName): void {
        try {
            $app = self::create($containerConfigClassName);
            $response = $app->handle($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
            $response->renderGlobal();
        } catch (HttpException $e) {
            http_response_code($e->statusCode);
            echo $e->statusCode, '<br />';
            echo nl2br($e->getTraceAsString());
        } catch (Throwable $e) {
            echo $e->getMessage(), '<br/>';
            echo nl2br($e->getTraceAsString());
        }
    }

    public static function create(string $containerConfigClassName): self {
        $container = Container::create($containerConfigClassName);
        $router = Router::create($container);
        return new self(
            $router
        );
    }

    /**
     * @throws Http\Exception\HttpException
     */
    public function handle(string $method, string $url): Response {
        return $this->router->handle($method, $url);
    }
}