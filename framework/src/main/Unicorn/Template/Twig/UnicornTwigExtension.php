<?php

namespace Unicorn\Template\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UnicornTwigExtension extends AbstractExtension {
    public function getFunctions() {
        return [
            new TwigFunction(
                'action',
                function (array $context, string $name) {
                    return $context['_router']->findAction($context['_controllerComponentName'], $name);
                },
                [
                    'needs_context' => true
                ]
            )
        ];
    }
}