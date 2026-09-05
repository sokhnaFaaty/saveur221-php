<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CommandeRepositoryInterface;
use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Commande;
use Exceptions\CommandeInexistanteException;
use Exceptions\TransitionStatutInvalideException;
use Exceptions\ValidationException;

class CommandeService
{
    public function __construct(
        private CommandeRepositoryInterface $commandes,
        private ProduitRepositoryInterface $produits,
    ) {}

    public function passerCommande(int $clientId, array $panier): Commande
    {
        return $this->commandes->create($clientId, $panier);
    }

    public function consulterCommande(int $id): Commande
    {
        return $this->commandes->findById($id)
            ?? throw new CommandeInexistanteException("Aucune commande trouvee avec l'id $id");
    }

    public function listerMesCommandes(int $clientId): array
    {
        return $this->commandes->findByClient($clientId);
    }

    public function listerCommandes(): array
    {
        return $this->commandes->findAll();
    }

    public function listerParStatut(string $statut): array
    {
        return $this->commandes->findByStatut($statut);
    }

    public function changerStatut(int $id, string $nouveauStatut): void
    {
        $commande = $this->consulterCommande($id);

        if (!$commande->peutTransitionnerVers($nouveauStatut)) {
            throw new TransitionStatutInvalideException(
                "Transition invalide : {$commande->statut} -> $nouveauStatut"
            );
        }

        if ($nouveauStatut === Commande::ANNULEE) {
            foreach ($commande->lignes as $ligne) {
                $this->produits->restaurerStock($ligne->produitId, $ligne->quantite);
            }
        }

        $this->commandes->updateStatut($id, $nouveauStatut);
    }

    public function annulerCommande(int $id): void
    {
        $this->changerStatut($id, Commande::ANNULEE);
    }
}