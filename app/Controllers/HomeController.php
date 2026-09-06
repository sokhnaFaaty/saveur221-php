<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AvisService;
use App\Services\CategorieService;
use App\Services\ProduitService;
use Core\View;

class HomeController extends Controller
{
    public function __construct(
        private CategorieService $categorieService,
        private ProduitService $produitService,
        private AvisService $avisService,
    ) {}

    public function index(): string
    {
        return View::render('home', [
            'title'      => 'Accueil',
            'categories' => $this->categorieService->listerCategories(),
            'plats'      => $this->produitService->listerProduitsDisponibles(),
            'avis'       => $this->avisService->listerTous(),
        ], 'layouts/public');
    }
}