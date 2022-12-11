<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;
use Unicorn\Template\TemplateContext;
use Unicorn\Template\TemplateEngine;

class TemplateResponseContentRenderer implements ResponseContentRenderer {
    public function __construct(
        private readonly string $name,
        private readonly array $data
    ) {
    }

    public function renderToStdOut(TemplateEngine $templateEngine, string $controllerDir) {
        $templateEngine->renderToStdOut($controllerDir, $this->name, $this->data);
    }
}