<?php

namespace Unicorn\Db;

use Attribute;
use Unicorn\Container\Import;

#[Attribute]
#[Import(DefaultPdoDatabaseConfig::class)]
class UsePdo {
}