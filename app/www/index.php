<?php

require __DIR__ . '/../vendor/autoload.php';

use TodoList\TodoListContainerConfiguration;
use Unicorn\App;

(new App(TodoListContainerConfiguration::class))->handleGlobalRequest();