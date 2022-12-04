<?php

namespace Unicorn\Template\Twig;

use Unicorn\Template\TemplateEngine;

class TwigTemplateContainerConfig {
    public function templateEngine(): TemplateEngine {
        return new TwigTemplateEngine();
    }
}