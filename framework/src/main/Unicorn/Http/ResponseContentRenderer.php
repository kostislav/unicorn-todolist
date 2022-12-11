<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;
use Unicorn\Template\TemplateEngine;

interface ResponseContentRenderer {
    public function renderToStdOut(ReverseRouter $reverseRouter, TemplateEngine $templateEngine, string $controllerComponentName, string $controllerDir);
}