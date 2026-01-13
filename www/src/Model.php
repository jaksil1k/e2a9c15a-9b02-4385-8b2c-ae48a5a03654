<?php

namespace App;

use PDO;

abstract class Model
{
    protected static string $table;
    protected static ?string $primaryKey = 'id';
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = Connection::instance()->pdo();
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findWhere(array $conditions): ?array
    {
        $sql = "SELECT * FROM " . static::$table . " WHERE ";
        $params = [];
        $clauses = [];

        foreach ($conditions as $column => $value) {
            $clauses[] = "`$column` = ?";
            $params[] = $value;
        }

        $sql .= implode(' AND ', $clauses);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: null;
    }

    public function insert(array $data): int
    {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), '?'));
        $stmt = $this->pdo->prepare("INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $set = implode(", ", array_map(fn($k) => "$k = ?", array_keys($data)));
        $stmt = $this->pdo->prepare("UPDATE " . static::$table . " SET $set WHERE " . static::$primaryKey . " = ?");
        return $stmt->execute([...array_values($data), $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM " . static::$table . " WHERE " . static::$primaryKey . " = ?");
        return $stmt->execute([$id]);
    }
    
    public function deleteWhere(array $conditions): bool
    {
        $whereParts = [];
        $values = [];

        foreach ($conditions as $column => $value) {
            $whereParts[] = "`$column` = ?";
            $values[] = $value;
        }

        $whereClause = implode(' AND ', $whereParts);

        $sql = "DELETE FROM " . static::$table . " WHERE " . $whereClause;
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($values);
    }
}
