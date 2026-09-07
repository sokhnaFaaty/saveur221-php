<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\View;

class ProfilController extends Controller
{
    public function index(): string
    {
        return View::render('profil/index', ['title' => 'Mon Profil & Securite'], 'layouts/dashboard');
    }
}