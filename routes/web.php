<?php

declare(strict_types=1);

use App\Controllers\HomeController;

/** @var \Core\Router $router */
$router->get('/', [HomeController::class, 'index']);