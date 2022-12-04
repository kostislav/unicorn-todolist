<?php

namespace Unicorn\Http\Exception;

use Exception;

class NotFoundException extends HttpException {
    public function __construct() {
        parent::__construct(404);
    }
}