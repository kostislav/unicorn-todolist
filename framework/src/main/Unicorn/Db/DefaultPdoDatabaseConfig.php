<?php

namespace Unicorn\Db;

class DefaultPdoDatabaseConfig {
    public function database(): SqlDatabase {
        return new SqlDatabase();
    }
}