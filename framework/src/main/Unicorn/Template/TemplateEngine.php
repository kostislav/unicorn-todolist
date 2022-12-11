<?php

namespace Unicorn\Template;

interface TemplateEngine {
    function renderToStdOut(TemplateContext $templateContext, string $baseDir, string $name, array $data): void;
}