<?php

namespace Unicorn\Template;

use Unicorn\Container\Container;

class LazyTemplateEngine implements TemplateEngine {
    public function __construct(
        private readonly Container $container
    ) {
    }

    function renderToStdOut(TemplateContext $templateContext, string $baseDir, string $name, array $data): void {
        $this->container->get('templateEngine')->renderToStdOut($templateContext, $baseDir, $name, $data);
    }
}