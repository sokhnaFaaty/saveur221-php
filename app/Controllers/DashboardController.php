<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\StatistiqueService;
use Core\View;

class DashboardController extends Controller
{
    public function __construct(private StatistiqueService $statistiqueService) {}

    public function index(): string
    {
        return View::render('dashboard/index', [
            'title'             => 'Tableau de Bord',
            'chiffreAffaires'   => $this->statistiqueService->chiffreAffairesDuJour(),
            'commandesEnCours'  => $this->statistiqueService->nombreCommandesEnCours(),
            'alertesStock'      => $this->statistiqueService->alertesStock(),
            'dernieresCommandes'=> $this->statistiqueService->dernieresCommandes(),
        ], 'layouts/dashboard');
    }
}