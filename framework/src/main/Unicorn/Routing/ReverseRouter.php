<?php

namespace Unicorn\Routing;

interface ReverseRouter {
    public function findAction(string $controllerComponentName, string $actionName): ActionRoute;
}