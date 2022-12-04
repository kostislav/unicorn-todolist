<?php

require __DIR__ . '/../vendor/autoload.php';

use TodoList\TodoListContainerConfiguration;
use Unicorn\App;

App::handleGlobalRequest(TodoListContainerConfiguration::class);