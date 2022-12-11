<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;
use Unicorn\Template\TemplateEngine;

class Response {
    /** @param ResponseHeaderRenderer[] $headers */
    private function __construct(
        private readonly ResponseContentRenderer $contentRenderer,
        private readonly int $status = 200,
        private readonly array $headers = []
    ) {
    }

    public static function plain(string $text): self {
        return new self(new PlainResponseContentRenderer($text));
    }

    public static function template(string $name, array $data = []): self {
        return new self(new TemplateResponseContentRenderer($name, $data));
    }

    public static function redirect(string $action) {
        return new self(
            new PlainResponseContentRenderer(''),
            status: 303,
            headers: [
                new RedirectHeaderRenderer($action)
            ]
        );
    }

    public function send(ReverseRouter $reverseRouter, TemplateEngine $templateEngine, string $controllerComponentName, string $controllerDir): void {
        http_response_code($this->status);
        foreach ($this->headers as $header) {
            $header->sendToOutput($reverseRouter, $controllerComponentName);
        }
        $this->contentRenderer->renderToStdOut($reverseRouter, $templateEngine, $controllerComponentName, $controllerDir);
    }
}