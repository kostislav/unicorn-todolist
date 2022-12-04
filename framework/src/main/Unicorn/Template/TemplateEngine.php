<?php

namespace Unicorn\Template;

interface TemplateEngine {
    function renderToStdOut(string $baseDir, string $name, array $data): void;
}