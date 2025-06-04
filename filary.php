#!/usr/bin/env php
<?php

$argv = $_SERVER['argv'];
$argc = $_SERVER['argc'];

if ($argc < 3 || $argv[1] !== 'create:module') {
    echo "Usage: php fylari create:module ModuleName [--type=entity|plugin|system]\n";
    exit(1);
}

$moduleName = ucfirst($argv[2]);
$moduleCode = strtolower($moduleName);

// Определение типа
$type = 'entity';
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--type=')) {
        $type = strtolower(explode('=', $arg)[1]);
    }
}

// === Генерация doc_prefix ===
function generatePrefix(string $code): string
{
    $consonants = array_filter(str_split(strtolower($code)), fn($c) => !in_array($c, ['a','e','i','o','u']));
    $base = strtoupper(substr($code, 0, 1));
    $prefix = $base;
    $i = 1;

    while (strlen($prefix) < 3 && isset($consonants[$i])) {
        $prefix .= strtoupper($consonants[$i]);
        $i++;
    }

    $attempt = $prefix;
    $suffix = 1;

    while (prefixExists($attempt)) {
        if (strlen($attempt) >= 3) {
            $attempt = substr($prefix, 0, -1) . $suffix;
            $suffix++;
        } else {
            $attempt .= $suffix++;
        }
    }

    return $attempt;
}

function prefixExists(string $prefix): bool
{
    $pdo = require __DIR__ . '/config/database.php';
    $stmt = $pdo->prepare("SELECT id FROM modules WHERE doc_prefix = ?");
    $stmt->execute([$prefix]);
    return (bool) $stmt->fetch();
}

// === Путь и префиксы ===
$prefix = "{$type}_{$moduleCode}";
$moduleDir = __DIR__ . "/app/Modules/$moduleName";
$controllerFile = "$moduleDir/{$moduleName}Controller.php";
$routesFile = "$moduleDir/Routes.php";
$sqlFile = "$moduleDir/{$prefix}.sql";

// === Создание структуры
if (!is_dir($moduleDir)) {
    mkdir($moduleDir, 0777, true);
}

// === Генерация controller
$controllerCode = <<<PHP
<?php

namespace Fylari\Modules\\$moduleName;

use Fylari\Core\Controller;
use Fylari\Core\Entity;
use Fylari\Core\Field;
use Fylari\Core\DB;

class {$moduleName}Controller extends Controller
{
    private Field \$field;
    private Entity \$entity;
    private int \$moduleId = 0; // TODO: Set module ID

    public function __construct()
    {
        \$this->field = new Field();
        \$this->entity = new Entity();
    }

    public function index(): void
    {
        \$this->json(DB::table('entities')->where('module_id', '=', \$this->moduleId)->get());
    }

    public function show(\$id): void
    {
        \$this->json(DB::table('entities')->where('id', '=', \$id)->first());
    }

    public function store(): void { \$this->json(['msg' => 'Not implemented']); }
    public function update(\$id): void { \$this->json(['msg' => 'Not implemented']); }
    public function destroy(\$id): void { \$this->json(['msg' => 'Not implemented']); }
    public function archive(\$id): void { \$this->json(['msg' => 'Not implemented']); }
    public function restore(\$id): void { \$this->json(['msg' => 'Not implemented']); }
}
PHP;

file_put_contents($controllerFile, $controllerCode);

// === Генерация Routes.php
$routesCode = <<<PHP
<?php

use Fylari\Core\Router;

/** @var Router \$router */

// Add extra routes here if needed
PHP;

file_put_contents($routesFile, $routesCode);

// === SQL scaffold с полем entity_id и *_num
$docNumField = "{$moduleCode}_num";

$sql = <<<SQL
CREATE TABLE {$prefix} (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_id INT NOT NULL,
    {$docNumField} VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (entity_id) REFERENCES entities(id) ON DELETE CASCADE
);

CREATE TABLE {$prefix}_cf (
    id INT AUTO_INCREMENT PRIMARY KEY,
    entity_id INT NOT NULL,
    field_key VARCHAR(100) NOT NULL,
    field_value TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (entity_id) REFERENCES entities(id) ON DELETE CASCADE
);
SQL;

file_put_contents($sqlFile, $sql);

// === Регистрация модуля в БД
$docPrefix = generatePrefix($moduleCode);
$pdo = require __DIR__ . '/config/database.php';

$stmt = $pdo->prepare("INSERT INTO modules (code, name, type, doc_prefix, doc_scope, active) VALUES (?, ?, ?, ?, ?, 1)");
$stmt->execute([$moduleCode, $moduleName, $type, $docPrefix, 'yearly']);

echo "✅ Module [$moduleName] scaffolded.\n";
echo "📁 Created: $controllerFile\n";
echo "📄 Tables: {$prefix}, {$prefix}_cf\n";
echo "🧾 Registered in modules: code={$moduleCode}, prefix={$docPrefix}, scope=yearly\n";
