<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\NotificationRepositoryInterface;

class NotificationService
{
    public function __construct(private NotificationRepositoryInterface $notifications) {}

    public function notifierNouvelleCommande(string $numCommande, int $commandeId): void
    {
        $this->notifications->create(
            'NOUVELLE_COMMANDE',
            "Nouvelle commande $numCommande",
            "/commandes/$commandeId",
            'GERANT'
        );
    }

    public function notifierStockFaible(string $libelleProduit, int $produitId, bool $rupture): void
    {
        $message = $rupture
            ? "Rupture de stock : $libelleProduit"
            : "Stock faible : $libelleProduit";

        $this->notifications->create('STOCK_FAIBLE', $message, "/produits/$produitId", 'GERANT');
    }

    public function notifierNouvelAvis(string $nomClient, int $commandeId): void
    {
        $this->notifications->create(
            'NOUVEL_AVIS',
            "Nouvel avis laisse par $nomClient",
            '/avis',
            'ADMIN'
        );
    }

    public function notifierCommandePrete(string $numCommande, int $commandeId, int $clientId): void
    {
        $this->notifications->create(
            'COMMANDE_PRETE',
            "Votre commande $numCommande est prete : vous pouvez venir la retirer.",
            "/mes-commandes?statut=PRETE",
            'CLIENT',
            $clientId
        );
    }

    public function listerPourRole(string $role): array
    {
        return $this->notifications->findForRole($role);
    }

    public function compterNonLues(string $role): int
    {
        return $this->notifications->countUnread($role);
    }

    public function marquerLue(int $id): void
    {
        $this->notifications->markAsRead($id);
    }

    public function listerPourClient(int $clientId): array
    {
        return $this->notifications->findForClient($clientId);
    }

    public function compterNonLuesClient(int $clientId): int
    {
        return $this->notifications->countUnreadForClient($clientId);
    }

    public function marquerLueClient(int $id, int $clientId): void
    {
        $this->notifications->markAsReadForClient($id, $clientId);
    }
}