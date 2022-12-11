<?php

namespace Unicorn\Routing;

interface ReverseRouter {
    public function findAction(string $controllerComponentName, string $actionName, array $args = []): ActionRoute;
}