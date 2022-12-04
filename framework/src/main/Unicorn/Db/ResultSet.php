<?php

namespace Unicorn\Db;

class ResultSet {
    public function __construct(
        private readonly array $items
    ) {
    }

    public function map(callable $transformation): self {
        return new self(array_map($transformation, $this->items));
    }

    public function toArray(): array {
        return $this->items;
    }
}