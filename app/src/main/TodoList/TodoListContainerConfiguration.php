<?php

namespace TodoList;

use Unicorn\Db\SqlDatabase;
use Unicorn\Db\UsePdo;
use Unicorn\Routing\Controller;
use Unicorn\Template\Twig\UseTwig;

#[UseTwig]
#[UsePdo]
class TodoListContainerConfiguration {
    public function todoListService(SqlDatabase $database): TodoListService {
        return new TodoListService($database);
    }

    #[Controller('/')]
    public function todoListController(TodoListService $todoListService): TodoListController {
        return new TodoListController($todoListService);
    }
}