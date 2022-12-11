<?php

namespace Unicorn\Http;

use InvalidArgumentException;
use Unicorn\Routing\ReverseRouter;

class RedirectHeaderRenderer implements ResponseHeaderRenderer {
    public function __construct(
        private string $action
    ) {
    }

    public function sendToOutput(ReverseRouter $reverseRouter, string $controllerComponentName) {
        $actionRoute = $reverseRouter->findAction($controllerComponentName, $this->action);
        if ($actionRoute->method != 'GET') {
            throw new InvalidArgumentException("Cannot redirect to non-GET action " . $this->action);
        } else {
            header('Location: ' . $actionRoute->url);
        }
    }
}