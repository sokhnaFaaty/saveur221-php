<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Database;

class HomeController extends Controller
{
    public function index(): string
    {
        Database::connect();
        return 'Connexion a la base de donnees reussie.';
    }
}