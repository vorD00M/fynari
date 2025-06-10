<?php

namespace Fylari\Core;

class Field
{
    public function schema(int $moduleId): array
    {
        return DB::table('fields')->where('module_id', '=', $moduleId)->get();
    }

    public function formatFieldValue(string $fieldType, mixed $value): mixed
    {
        if (!$value) return null;

        try {
            return match ($fieldType) {
                'date'     => (new \DateTime($value))->format('Y-m-d'),
                'datetime' => (new \DateTime($value))->format('Y-m-d H:i:s'),
                default    => $value
            };
        } catch (\Exception) {
            return $value;
        }
    }
}
