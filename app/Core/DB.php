<?php

namespace core;

use PDO;

class DB
{
    private PDO $pdo;
    private string $table = '';
    private array $wheres = [];
    private array $bindings = [];

    public function __construct()
    {
        $this->pdo = require __DIR__ . '/../../../config/database.php';
    }

    public static function table(string $table): self
    {
        $instance = new self();
        $instance->table = $table;
        return $instance;
    }

    public function where(string $column, string $operator, mixed $value): self
    {
        $this->wheres[] = "$column $operator ?";
        $this->bindings[] = $value;
        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bindings);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): ?array
    {
        return $this->get()[0] ?? null;
    }

    public function insert(array $data): bool|int
    {
        $columns = implode(',', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($values) ? (int) $this->pdo->lastInsertId() : false;
    }

    public function update(array $data): bool
    {
        $set = implode(', ', array_map(fn($k) => "$k = ?", array_keys($data)));
        $values = array_values($data);

        $sql = "UPDATE {$this->table} SET $set";
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([...$values, ...$this->bindings]);
    }

    public function delete(): bool
    {
        $sql = "DELETE FROM {$this->table}";
        if ($this->wheres) {
            $sql .= " WHERE " . implode(' AND ', $this->wheres);
        }
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->bindings);
    }
}
