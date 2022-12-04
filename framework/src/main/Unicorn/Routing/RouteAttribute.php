<?php

namespace Unicorn\Routing;

interface RouteAttribute {
    function getMethod(): string;

    function getUrl(): string;
}