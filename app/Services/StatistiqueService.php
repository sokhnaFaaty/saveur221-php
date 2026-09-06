<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CommandeRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Commande;

class StatistiqueService
{
    public function __construct(
        private CommandeRepositoryInterface $commandes,
        private PaiementRepositoryInterface $paiements,
        private ProduitRepositoryInterface $produits,
    ) {}

    public function chiffreAffairesDuJour(): float
    {
        $aujourdhui = date('Y-m-d');
        $total = 0.0;
        foreach ($this->paiements->findAll() as $p) {
            if (substr($p->datePaiement, 0, 10) === $aujourdhui) {
                $total += $p->montant;
            }
        }
        return $total;
    }

    public function nombreCommandesEnCours(): int
    {
        $enCours = [Commande::EN_ATTENTE, Commande::EN_PREPARATION, Commande::PRETE];
        return count(array_filter($this->commandes->findAll(), fn ($c) => in_array($c->statut, $enCours, true)));
    }

    public function alertesStock(): array
    {
        return array_merge($this->produits->findEnRupture(), $this->produits->findStockFaible());
    }

    public function dernieresCommandes(int $limite = 5): array
    {
        return array_slice($this->commandes->findAll(), 0, $limite);
    }
}