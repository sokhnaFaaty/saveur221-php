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
        View::redirect('/commandes/' . $commandeId);
    }

    // ADMIN : moderation
    public function index(): string
    {
        return View::render('avis/index', ['title' => 'Moderation des avis', 'avis' => $this->avisService->listerTous()], null);
    }

    public function delete(int $id): never
    {
        $this->avisService->supprimerAvis($id);
        flash('success', 'Avis supprime.');
        View::redirect('/avis');
    }
}