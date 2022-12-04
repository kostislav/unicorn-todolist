<?php

namespace Unicorn\Container;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ConfigFile {
    public function __construct(
        public readonly string $relativePath
    ) {
    }
}