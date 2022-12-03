<?php

namespace TodoList;

use Unicorn\Form\Label;

class TodoForm {
    #[Label('New Todo Item')]
    public string $name;
}