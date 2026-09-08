<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\StatistiqueService;
use Core\View;

class StatistiqueController extends Controller
{
    public function __construct(private StatistiqueService $statistiqueService) {}

    public function index(): string
    {
        return View::render('statistiques/index', [
            'title'             => 'Rapports & Statistiques',
            'chiffreAffaires'   => $this->statistiqueService->chiffreAffairesTotal(),
            'nombreCommandes'   => $this->statistiqueService->nombreTotalCommandes(),
            'panierMoyen'       => $this->statistiqueService->panierMoyen(),
            'repartitionMoyens' => $this->statistiqueService->repartitionParMoyenDePaiement(),
        ], 'layouts/dashboard');
    }
}