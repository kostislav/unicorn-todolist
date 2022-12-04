<?php

namespace Unicorn\Http\Exception;

use Exception;

class HttpException extends Exception {
    public function __construct(
        public readonly int $statusCode
    ) {
        parent::__construct();
    }
}