<?php

namespace Fylari\Core;

class DocumentNumber
{
    public function generate(string $moduleCode): string
    {
        $now = new \DateTime();

        $module = DB::table('modules')->where('code', '=', $moduleCode)->first();
        if (!$module) throw new \Exception("Module [$moduleCode] not found");

        $scope = $module['doc_scope'] ?? 'yearly';
        $prefix = $module['doc_prefix'] ?? $this->generatePrefix($moduleCode);

        $datePrefix = match ($scope) {
            'yearly' => $now->format('y'),
            'monthly' => $now->format('ym'),
            'daily' => $now->format('ymd'),
            default => ''
        };

        $entry = DB::table('document_counters')
            ->where('module_code', '=', $moduleCode)
            ->where('counter_scope', '=', $scope)
            ->where('counter_prefix', '=', $datePrefix)
            ->first();

        if ($entry) {
            $num = $entry['current_number'] + 1;
            DB::table('document_counters')->where('id', '=', $entry['id'])->update(['current_number' => $num]);
        } else {
            $num = 1;
            DB::table('document_counters')->insert([
                'module_code' => $moduleCode,
                'module_prefix' => $prefix,
                'counter_scope' => $scope,
                'counter_prefix' => $datePrefix,
                'current_number' => 1
            ]);
        }

        return $prefix . ($datePrefix ? "-$datePrefix" : '') . '-' . str_pad($num, 6, '0', STR_PAD_LEFT);
    }

    private function generatePrefix(string $code): string
    {
        $consonants = array_filter(str_split($code), fn($c) => !in_array($c, ['a','e','i','o','u']));
        $prefix = strtoupper(substr($code, 0, 1));
        $i = 1;
        while (strlen($prefix) < 3 && isset($consonants[$i])) {
            $prefix .= strtoupper($consonants[$i++]);
        }

        $attempt = $prefix;
        $suffix = 1;

        while (DB::table('modules')->where('doc_prefix', '=', $attempt)->first()) {
            $attempt = substr($prefix, 0, -1) . $suffix++;
        }

        return $attempt;
    }
}
