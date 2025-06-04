<?php

namespace App\Modules\Entity;

use PDO;

class Entity
{
    private PDO $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../../config/database.php';
    }

    public function create(string $module, string $name): int
    {
        $stmt = $this->db->prepare("INSERT INTO entities (module, name) VALUES (?, ?)");
        $stmt->execute([$module, $name]);
        return (int)$this->db->lastInsertId();
    }

    public function all(string $module): array
    {
        $stmt = $this->db->prepare("SELECT * FROM entities WHERE module = ?");
        $stmt->execute([$module]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM entities WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM entities WHERE id = ?");
        return $stmt->execute([$id]);
    }
}


