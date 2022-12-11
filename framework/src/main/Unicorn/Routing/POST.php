<?php

namespace Unicorn\Routing;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class POST implements RouteAttribute {
    public function __construct(
        private readonly string $url
    ) {
    }

    function getMethod(): string {
        return "POST";
    }

    function getUrl(): string {
        return $this->url;
    }
}