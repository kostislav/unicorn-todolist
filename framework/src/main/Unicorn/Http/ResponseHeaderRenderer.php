<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;

interface ResponseHeaderRenderer {
    public function sendToOutput(ReverseRouter $reverseRouter, string $controllerComponentName);
}