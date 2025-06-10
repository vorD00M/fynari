<?php

use Fylari\Core\Router;
use Fylari\Modules\Modules\ModulesController;


$router->get('/modules', [ModulesController::class, 'index']);
$router->put('/modules/{id}', [ModulesController::class, 'update']);

