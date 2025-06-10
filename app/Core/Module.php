<?php

namespace Fylari\Core;

class Module
{
    public function all(bool $onlyActive = true): array
    {
        $q = DB::table('modules');
        if ($onlyActive) $q = $q->where('active', '=', 1);
        return $q->get();
    }

    public function findByCode(string $code): ?array
    {
        return DB::table('modules')->where('code', '=', $code)->first();
    }

    public function getByType(string $type): array
    {
        return DB::table('modules')->where('type', '=', $type)->get();
    }

    public function enable(int $id): bool
    {
        return DB::table('modules')->where('id', '=', $id)->update(['active' => 1]);
    }

    public function disable(int $id): bool
    {
        return DB::table('modules')->where('id', '=', $id)->update(['active' => 0]);
    }
}
