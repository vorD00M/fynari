<?php

use App\Modules\Contact\ContactController;

$router->get('/contacts', [ContactController::class, 'index']);
$router->get('/contacts/{id}', [ContactController::class, 'show']);
$router->post('/contacts', [ContactController::class, 'store']);
$router->put('/contacts/{id}', [ContactController::class, 'update']);
$router->delete('/contacts/{id}', [ContactController::class, 'delete']);

