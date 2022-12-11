<?php

namespace Unicorn\Template\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UnicornTwigExtension extends AbstractExtension {
    public function getFunctions() {
        return [
            new TwigFunction(
                'action',
                function (array $context, string $name, array $args = []) {
                    return $context['_router']->findAction($context['_controllerComponentName'], $name, $args);
                },
                [
                    'needs_context' => true
                ]
            )
        ];
    }
}