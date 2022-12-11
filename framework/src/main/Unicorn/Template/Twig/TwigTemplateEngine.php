<?php

namespace Unicorn\Template\Twig;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Unicorn\Template\TemplateContext;
use Unicorn\Template\TemplateEngine;

class TwigTemplateEngine implements TemplateEngine {
    private readonly Environment $twig;

    public function __construct() {
        // TODO not very secure - use baseDir when available
        $loader = new FilesystemLoader([FilesystemLoader::MAIN_NAMESPACE => '/'], '/');
        $this->twig = new Environment($loader, ['cache' => false]);
        $this->twig->addExtension(new UnicornTwigExtension());
    }

    function renderToStdOut(TemplateContext $templateContext, string $baseDir, string $name, array $data): void {
        $template = $this->twig->load($baseDir . '/' . $name . '.twig');
        $template->display($data + [
                '_controllerComponentName' => $templateContext->controllerComponentName,
                '_router' => $templateContext->router
            ]);
    }
}