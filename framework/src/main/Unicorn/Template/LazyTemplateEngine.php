<?php

namespace Unicorn\Template;

use Unicorn\Container\Container;

class LazyTemplateEngine implements TemplateEngine {
    public function __construct(
        private readonly Container $container
    ) {
    }

    function renderToStdOut(string $baseDir, string $name, array $data): void {
        $this->container->get('templateEngine')->renderToStdOut($baseDir, $name, $data);
    }
}