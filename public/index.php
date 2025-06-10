<?php
ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';

use Fylari\Core\Router;
use Fylari\Middleware\Kernel;

// ===== Middleware Entry Point =====
$kernel = new Kernel();
$kernel->handleRequestHeaders();   // CORS, Content-Type, OPTIONS и т.д.

// ===== Router Dispatch =====
$router = new Router();
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
