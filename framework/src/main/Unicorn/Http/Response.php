<?php

namespace Unicorn\Http;

use Unicorn\Template\TemplateEngine;

class Response {
    private function __construct(
        private readonly ?string $content,
        private readonly ?string $templateName,
        private readonly ?array $templateData
    ) {
    }

    public static function plain($text): self {
        return new self($text, null, null);
    }

    public static function template(string $name, array $data = []): self {
        return new self(null, $name, $data);
    }

    public function send(TemplateEngine $templateEngine, string $controllerDir): void {
        if ($this->content != null) {
            echo $this->content;
        } else {
            $templateEngine->renderToStdOut($controllerDir, $this->templateName, $this->templateData);
        }
    }
}