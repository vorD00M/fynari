<?php

use Fylari\Core\Router;
use Fylari\Modules\Users\UsersController;

/** @var Router $router */

$router->post('/users/register', [UsersController::class, 'register']);
$router->post('/users/login',    [UsersController::class, 'login']);
$router->get('/users/me',        [UsersController::class, 'me']);
$router->post('/users/logout',   [UsersController::class, 'logout']);
$router->get('/users',           [UsersController::class, 'index']);
$router->get('/users/{id}',      [UsersController::class, 'show']);
$router->put('/users/{id}',      [UsersController::class, 'update']);
$router->put('/users/profile', [UsersController::class, 'updateProfile']);
$router->put('/users/password', [UsersController::class, 'changePassword']);
$router->post('/users',           [UsersController::class, 'create']);    // 🔐 Admin only
