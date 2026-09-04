<?php

declare(strict_types=1);

use Core\Container;
use Core\Router;
use Core\View;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

session_start();

define('VIEW_PATH', dirname(__DIR__) . '/views');

$container = new Container();



$router = new Router($container);
require __DIR__ . '/../routes/web.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$base = View::baseUrl();

if ($base !== '' && str_starts_with($path, $base)) {
    $path = substr($path, strlen($base));
}
if ($path === '') {
    $path = '/';
}

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $path);
} catch (\Throwable $e) {
    http_response_code(500);
    echo View::render('errors/500', [
        'title'   => 'Erreur serveur',
        'message' => $e->getMessage(),
    ], null);
}