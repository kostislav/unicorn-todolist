<?php

namespace Unicorn\Routing;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Controller {
    public function __construct(
        public readonly string $baseUrl
    ) {
    }
}