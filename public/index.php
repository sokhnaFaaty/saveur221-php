<?php

declare(strict_types=1);

use Core\Container;
use Core\Router;
use Core\View;
use Dotenv\Dotenv;
use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\UtilisateurRepositoryInterface;
use App\Interfaces\RememberTokenRepositoryInterface;
use App\Repositories\ClientRepository;
use App\Repositories\UtilisateurRepository;
use App\Repositories\RememberTokenRepository;
use App\Interfaces\CategorieRepositoryInterface;
use App\Interfaces\CommandeRepositoryInterface;
use App\Repositories\CommandeRepository;
use App\Repositories\CategorieRepository;
use App\Interfaces\ProduitRepositoryInterface;
use App\Repositories\ProduitRepository;
use App\Interfaces\FactureRepositoryInterface;
use App\Repositories\FactureRepository;
use App\Interfaces\PaiementRepositoryInterface;
use App\Interfaces\RecuRepositoryInterface;
use App\Repositories\PaiementRepository;
use App\Repositories\RecuRepository;
use App\Interfaces\NotificationRepositoryInterface;
use App\Repositories\NotificationRepository;
use App\Interfaces\AvisRepositoryInterface;
use App\Repositories\AvisRepository;





require __DIR__ . '/../vendor/autoload.php';

Dotenv::createImmutable(dirname(__DIR__))->safeLoad();

session_start();

define('VIEW_PATH', dirname(__DIR__) . '/views');

$container = new Container();

$container->bind(ClientRepositoryInterface::class, fn () => new ClientRepository());
$container->bind(UtilisateurRepositoryInterface::class, fn () => new UtilisateurRepository());
$container->bind(RememberTokenRepositoryInterface::class, fn () => new RememberTokenRepository());
$container->bind(CategorieRepositoryInterface::class, fn () => new CategorieRepository());
$container-> bind(ProduitRepositoryInterface::class, fn () => new ProduitRepository());
$container->bind(CommandeRepositoryInterface::class, fn ($c) => new CommandeRepository($c->make(ProduitRepositoryInterface::class)));
$container->bind(FactureRepositoryInterface::class, fn () => new FactureRepository());
$container->bind(PaiementRepositoryInterface::class, fn () => new PaiementRepository());
$container->bind(RecuRepositoryInterface::class, fn () => new RecuRepository());
$container->bind(NotificationRepositoryInterface::class, fn () => new NotificationRepository());
$container->bind(AvisRepositoryInterface::class, fn () => new AvisRepository());

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