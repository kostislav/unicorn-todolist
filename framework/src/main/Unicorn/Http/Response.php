<?php

namespace Unicorn\Http;

use Unicorn\Template\TemplateEngine;

class Response {
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

    public static function redirect(string $url) {
        return new self(
            new PlainResponseContentRenderer(''),
            status: 303,
            headers: [
                'Location' => $url
            ]
        );
    }

    public function send(TemplateEngine $templateEngine, string $controllerDir): void {
        http_response_code($this->status);
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }
        $this->contentRenderer->renderToStdOut($templateEngine, $controllerDir);
    }
}