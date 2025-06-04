<?php

namespace App\Modules\Entity;

use PDO;

class CustomField
{
    private PDO $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../../config/database.php';
    }

    public function add(int $entityId, string $key, string $value): bool
    {
        $stmt = $this->db->prepare("INSERT INTO custom_fields (entity_id, field_key, field_value) VALUES (?, ?, ?)");
        return $stmt->execute([$entityId, $key, $value]);
    }

    public function update(int $entityId, string $key, string $value): bool
    {
        $stmt = $this->db->prepare("UPDATE custom_fields SET field_value = ? WHERE entity_id = ? AND field_key = ?");
        return $stmt->execute([$value, $entityId, $key]);
    }

    public function get(int $entityId): array
    {
        $stmt = $this->db->prepare("SELECT field_key, field_value FROM custom_fields WHERE entity_id = ?");
        $stmt->execute([$entityId]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public function delete(int $entityId, string $key): bool
    {
        $stmt = $this->db->prepare("DELETE FROM custom_fields WHERE entity_id = ? AND field_key = ?");
        return $stmt->execute([$entityId, $key]);
    }
}

