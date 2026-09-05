<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\AvisRepositoryInterface;
use App\Interfaces\CommandeRepositoryInterface;
use App\Models\Avis;
use App\Models\Commande;
use Exceptions\AccesRefuseeException;
use Exceptions\ValidationException;

class AvisService
{
    public function __construct(
        private AvisRepositoryInterface $avis,
        private CommandeRepositoryInterface $commandes,
        private NotificationService $notifications,
    ) {}

    public function laisserAvis(int $clientId, int $commandeId, int $note, ?string $commentaire): Avis
    {
        if ($note < 1 || $note > 5) {
            throw new ValidationException('La note doit etre comprise entre 1 et 5.');
        }

        $commande = $this->commandes->findById($commandeId);
        if ($commande === null || $commande->clientId !== $clientId) {
            throw new AccesRefuseeException("Cette commande ne vous appartient pas.");
        }
        if ($commande->statut !== Commande::RETIREE) {
            throw new ValidationException('Vous ne pouvez laisser un avis que sur une commande retiree.');
        }

        $avis = $this->avis->create($clientId, $commandeId, $note, $commentaire);

        $this->notifications->notifierNouvelAvis(
            $avis->clientPrenom . ' ' . $avis->clientNom,
            $commandeId
        );

        return $avis;
    }

    public function listerTous(): array
    {
        return $this->avis->findAll();
    }

    public function supprimerAvis(int $id): void
    {
        $this->avis->delete($id);
    }
}