<?php

namespace Unicorn\Http;

use Unicorn\Routing\ReverseRouter;
use Unicorn\Template\TemplateEngine;

interface ResponseContentRenderer {
    public function renderToStdOut(TemplateEngine $templateEngine, string $controllerDir);
}