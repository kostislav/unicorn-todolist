<?php

namespace Unicorn\Routing;

class ActionRoute {
    public function __construct(
        public readonly string $url,
        public readonly string $method
    ) {
    }

    public function prefixedWith(string $prefix): ActionRoute {
        // TODO double slash more
        if (str_ends_with('/', $prefix) && str_starts_with('/', $this->url)) {
            return new ActionRoute($prefix . substr($this->url, 1), $this->method);
        } else {
            return new ActionRoute($prefix . $this->url, $this->method);
        }
    }

    public function __toString(): string {
        return $this->url;
    }
}