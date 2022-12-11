<?php

namespace Unicorn\Template;

use Unicorn\Routing\ReverseRouter;

class TemplateContext {
    public function __construct(
        public readonly string $controllerComponentName,
        public readonly ReverseRouter $router
    ) {
    }
}