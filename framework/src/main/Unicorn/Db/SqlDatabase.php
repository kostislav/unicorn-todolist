<?php

namespace Unicorn\Db;

use PDO;

class SqlDatabase {
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function selectSimple(array $columns, string $table, array $where = []): ResultSet {
        $query = 'SELECT `' . implode('`, `', $columns) . '` FROM `' . $table . '`';
        if (empty($where)) {
            $statement = $this->pdo->prepare($query);
        } else {
            $placeholders = [];
            foreach ($where as $column => $value) {
                $placeholders[] = '`' . $column . '` = :' . $column;
            }
            $query .= ' WHERE ' . implode(' AND ', $placeholders);
            $statement = $this->pdo->prepare($query);
            foreach ($where as $column => $value) {
                if (is_bool($value)) {
                    $statement->bindValue($column, $value ? 1 : 0, PDO::PARAM_INT);
                } else {
                    $statement->bindValue($column, $value);
                }
            }
        }
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        return new ResultSet($statement->fetchAll());
    }
}