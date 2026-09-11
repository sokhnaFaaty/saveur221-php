<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AvisService;
use Core\View;
use Exceptions\AppException;

class AvisController extends Controller
{
    public function __construct(private AvisService $avisService) {}

    public function store(int $commandeId): never
    {
        try {
            $this->avisService->laisserAvis(
                (int) $_SESSION['user']['id'],
                $commandeId,
                (int) $this->value('note', 0),
                $this->value('commentaire')
            );
            flash('success', 'Merci pour votre avis !');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/mes-commandes');
    }

    // ADMIN : moderation
    public function index(): string
    {
        $pagination = paginer($this->avisService->listerTous(), (int) $this->value('page', 1));
        return View::render('avis/index', [
            'title' => 'Moderation des avis',
            'avis' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ], 'layouts/dashboard');
    }

    public function delete(int $id): never
    {
        $this->avisService->supprimerAvis($id);
        flash('success', 'Avis supprime.');
        View::redirect('/avis');
    }

    public function corbeille(): string
    {
        $pagination = paginer($this->avisService->listerAvisSupprimes(), (int) $this->value('page', 1));
        return View::render('avis/corbeille', [
            'title' => 'Corbeille des avis',
            'avis' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ], 'layouts/dashboard');
    }

    public function restaurer(int $id): never
    {
        $this->avisService->restaurerAvis($id);
        flash('success', 'Avis restaure.');
        View::redirect('/avis/corbeille');
    }

    public function supprimerDefinitivement(int $id): never
    {
        $this->avisService->supprimerAvisDefinitivement($id);
        flash('success', 'Avis supprime definitivement.');
        View::redirect('/avis/corbeille');
    }
}