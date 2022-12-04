<?php

namespace Unicorn\Container;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Import {
    public function __construct(
        public readonly array $containerConfigClassNames
    ) {
    }
}