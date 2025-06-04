<?php

namespace Fylari\Core;

class Module
{
    public function all(bool $onlyActive = true): array
    {
        $query = DB::table('modules');
        if ($onlyActive) {
            $query = $query->where('active', '=', 1);
        }
        return $query->get();
    }

    public function findByCode(string $code): ?array
    {
        return DB::table('modules')
            ->where('code', '=', $code)
            ->first();
    }

    public function getByType(string $type): array
    {
        return DB::table('modules')
            ->where('type', '=', $type)
            ->get();
    }

    public function enable(int $moduleId): bool
    {
        return DB::table('modules')->where('id', '=', $moduleId)->update(['active' => 1]);
    }

    public function disable(int $moduleId): bool
    {
        return DB::table('modules')->where('id', '=', $moduleId)->update(['active' => 0]);
    }
}
