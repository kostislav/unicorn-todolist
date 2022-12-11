<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;

class StaticHeaderRenderer implements ResponseHeaderRenderer {
    public function __construct(
        private string $name,
        private string $value
    ) {
    }

    public function sendToOutput(ReverseRouter $reverseRouter, string $controllerComponentName) {
        header($this->name . ': ' . $this->value);
    }
}