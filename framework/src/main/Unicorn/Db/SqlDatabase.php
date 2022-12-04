<?php

namespace Unicorn\Db;

use PDO;

class SqlDatabase {
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function selectSimple(array $columns, string $table, array $where = []): ResultSet {
        return new ResultSet([]);
    }
}