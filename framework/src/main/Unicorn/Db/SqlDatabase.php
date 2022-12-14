<?php

namespace Unicorn\Db;

use PDO;
use PDOStatement;

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
            $query .= $this->buildWhere($where);
            $statement = $this->prepareAndBind($query, $where);
        }
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $statement->execute();
        return new ResultSet($statement->fetchAll());
    }

    public function insertSimple(string $table, array $columns): int {
        $columnNames = [];
        $placeholders = [];
        foreach ($columns as $column => $value) {
            $columnNames[] = $column;
            $placeholders[] = ':' . $column;
        }
        $query = "INSERT INTO `{$table}`(`" . implode('`, `', $columnNames) . '`) VALUES (' . implode(',', $placeholders) . ')';
        $statement = $this->prepareAndBind($query, $columns);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function updateSimple($table, $columns, $where): int {
        $assignments = [];
        foreach ($columns as $column => $value) {
            $assignments[] = "`{$column}` = :{$column}";
        }
        $query = "UPDATE `{$table}` SET " . implode(', ', $assignments) . $this->buildWhere($where);
        $statement = $this->prepareAndBind($query, $columns + $where);
        $statement->execute();
        return $statement->rowCount();
    }

    private function prepareAndBind(string $query, array $params): PDOStatement {
        $statement = $this->pdo->prepare($query);
        foreach ($params as $name => $value) {
            if (is_bool($value)) {
                $statement->bindValue($name, $value ? 1 : 0, PDO::PARAM_INT);
            } else {
                $statement->bindValue($name, $value);
            }
        }
        return $statement;
    }

    private function buildWhere(array $where): string {
        $placeholders = [];
        foreach ($where as $column => $value) {
            $placeholders[] = '`' . $column . '` = :' . $column;
        }
        return ' WHERE ' . implode(' AND ', $placeholders);
    }
}