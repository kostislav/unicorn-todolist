<?php

namespace Unicorn\Routing;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class GET implements RouteAttribute {
    public function __construct(
        private readonly string $url
    ) {
    }

    function getMethod(): string {
        return "GET";
    }

    function getUrl(): string {
        return $this->url;
    }
}