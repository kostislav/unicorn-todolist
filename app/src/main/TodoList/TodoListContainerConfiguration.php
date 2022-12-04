<?php

namespace TodoList;

use Unicorn\Routing\Controller;
use Unicorn\Container\Import;
use Unicorn\Db\DefaultPdoDatabaseConfig;
use Unicorn\Db\SqlDatabase;

#[Import(DefaultPdoDatabaseConfig::class)]
class TodoListContainerConfiguration {
    public function todoListService(SqlDatabase $database): TodoListService {
        return new TodoListService($database);
    }

    #[Controller('/')]
    public function todoListController(TodoListService $todoListService): TodoListController {
        return new TodoListController($todoListService);
    }
}