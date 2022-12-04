<?php

namespace Unicorn\Db;

class PdoConfig {
    public function __construct(
        public readonly string $url,
        public readonly string $username,
        public readonly string $password
    ) {
    }
}