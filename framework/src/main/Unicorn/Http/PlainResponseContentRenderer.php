<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;
use Unicorn\Template\TemplateEngine;

class PlainResponseContentRenderer implements ResponseContentRenderer {
    public function __construct(
        private readonly string $body
    ) {
    }

    public function renderToStdOut(TemplateEngine $templateEngine, string $controllerDir) {
        echo $this->body;
    }
}