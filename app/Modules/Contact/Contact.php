<?php

namespace App\Modules\Contact;

use PDO;

class Contact
{
    private PDO $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../../../config/database.php';
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM contacts");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO contacts (first_name, last_name, email, phone, position, company_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['position'],
            $data['company_id']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE contacts SET
            first_name = ?, last_name = ?, email = ?, phone = ?, position = ?, company_id = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['position'],
            $data['company_id'],
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM contacts WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
