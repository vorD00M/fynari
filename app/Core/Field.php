<?php

namespace core;

class Field
{
    public function schema(int $moduleId): array
    {
        return DB::table('fields')
            ->where('module_id', '=', $moduleId)
            ->get();
    }

    public function formatFieldValue(string $fieldType, mixed $value): mixed
    {
        if (!$value) return null;

        try {
            switch ($fieldType) {
                case 'date':
                    return (new \DateTime($value))->format('Y-m-d');
                case 'datetime':
                    return (new \DateTime($value))->format('Y-m-d H:i:s');
                default:
                    return $value;
            }
        } catch (\Exception) {
            return $value;
        }
    }
}
