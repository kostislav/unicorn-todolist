<?php

namespace TodoList;

class TodoItem {
    public function __construct(
        public readonly int $id,
        public readonly string $name
    ) {
    }
}