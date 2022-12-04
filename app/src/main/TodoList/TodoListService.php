<?php

namespace TodoList;

use Unicorn\Db\SqlDatabase;

class TodoListService {
    private const TABLE = 'list_items';
    // columns
    private const ID = 'id';
    private const NAME = 'name';
    private const IS_COMPLETE = 'is_complete';

    public function __construct(
        private readonly SqlDatabase $database
    ) {
    }

    /** @return TodoItem[] */
    public function getAllUnfinished(): array {
        return $this->database->selectSimple(
            [
                self::ID,
                self::NAME
            ],
            self::TABLE,
            [
                self::IS_COMPLETE => false
            ]
        )
            ->map(fn($item) => new TodoItem($item[self::ID], $item[self::NAME]))
            ->toArray();
    }

    public function create(string $name): void {
        $this->database->insertSimple(
            self::TABLE,
            [
                self::NAME => $name
            ]
        );
    }

    public function markComplete(int $id): void {
        $this->database->updateSimple(
            self::TABLE,
            [
                self::IS_COMPLETE => true
            ],
            [
                self::ID => $id
            ]
        );
    }
}