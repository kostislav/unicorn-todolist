<?php

namespace Unicorn\Http;

class Response {
    private function __construct(
        private readonly string $content
    ) {
    }

    public static function plain($text): self {
        return new self($text);
    }

    public function renderGlobal(): void {
        echo $this->content;
    }
}