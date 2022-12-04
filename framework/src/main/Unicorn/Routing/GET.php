<?php

namespace Unicorn\Routing;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class GET {
    public function __construct(
        public readonly string $url
    ) {
    }
}