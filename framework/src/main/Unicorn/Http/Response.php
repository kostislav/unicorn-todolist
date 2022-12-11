<?php

namespace Unicorn\Http;

use Unicorn\Template\TemplateEngine;

class Response {
    private function __construct(
        private readonly ResponseContentRenderer $contentRenderer
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
            new PlainResponseContentRenderer('')
        );
    }

    public function send(TemplateEngine $templateEngine, string $controllerDir): void {
        $this->contentRenderer->renderToStdOut($templateEngine, $controllerDir);
    }
}