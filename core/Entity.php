<?php

namespace Fylari\Core;

class Entity
{
    public function create(int $moduleId, string $name, ?string $description = null, ?int $ownerId = null): int
    {
        return DB::table('entities')->insert([
            'module_id' => $moduleId,
            'name' => $name,
            'description' => $description,
            'owner_id' => $ownerId,
        ]);
    }

    public function delete(int $entityId, int $userId): bool
    {
        return DB::table('entities')
            ->where('id', '=', $entityId)
            ->update([
                'deleted_at' => date('Y-m-d H:i:s'),
                'deleted_by' => $userId,
                'updated_by' => $userId,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function archive(int $entityId, int $userId): bool
    {
        return DB::table('entities')
            ->where('id', '=', $entityId)
            ->update([
                'status' => 2,
                'updated_by' => $userId,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function restore(int $entityId, int $userId): bool
    {
        return DB::table('entities')
            ->where('id', '=', $entityId)
            ->update([
                'status' => 1,
                'updated_by' => $userId,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function find(int $id): ?array
    {
        return DB::table('entities')
            ->where('id', '=', $id)
            ->first();
    }

    public function all(int $moduleId): array
    {
        return DB::table('entities')
            ->where('module_id', '=', $moduleId)
            ->where('deleted_at', 'IS', 'NULL')
            ->get();
    }
}
