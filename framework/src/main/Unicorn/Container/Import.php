<?php

namespace Unicorn\Container;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Import {
    public readonly array $containerConfigClassNames;

    public function __construct(array|string $containerConfigClassNames) {
        if (is_array($containerConfigClassNames)) {
            $this->containerConfigClassNames = $containerConfigClassNames;
        } else {
            $this->containerConfigClassNames = [$containerConfigClassNames];
        }
    }
}