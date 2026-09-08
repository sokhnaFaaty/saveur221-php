<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CommandeRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Commande;
use App\Models\Paiement;

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

    /**
     * Cumul des encaissements (tous les paiements, toutes périodes).
     */
    public function chiffreAffairesTotal(): float
    {
        $total = 0.0;
        foreach ($this->paiements->findAll() as $p) {
            $total += $p->montant;
        }
        return $total;
    }

    /**
     * Nombre total de commandes reçues (hors commandes annulées).
     */
    public function nombreTotalCommandes(): int
    {
        return count(array_filter(
            $this->commandes->findAll(),
            fn ($c) => $c->statut !== Commande::ANNULEE
        ));
    }

    /**
     * Panier moyen : chiffre d'affaires total / nombre de commandes (hors annulées).
     */
    public function panierMoyen(): float
    {
        $nbCommandes = $this->nombreTotalCommandes();
        if ($nbCommandes === 0) {
            return 0.0;
        }
        return round($this->chiffreAffairesTotal() / $nbCommandes, 2);
    }

    /**
     * Répartition des encaissements par moyen de paiement, avec part en pourcentage.
     *
     * @return array<string, array{montant: float, pourcentage: float}>
     */
    public function repartitionParMoyenDePaiement(): array
    {
        [$total, $parMoyen] = [0.0, [
            Paiement::WAVE => 0.0,
            Paiement::ORANGE_MONEY => 0.0,
            Paiement::ESPECES => 0.0,
        ]];

        foreach ($this->paiements->findAll() as $p) {
            $total += $p->montant;
            if (isset($parMoyen[$p->moyen])) {
                $parMoyen[$p->moyen] += $p->montant;
            }
        }

        foreach ($parMoyen as &$montant) {
            $montant = ['montant' => $montant, 'pourcentage' => $total > 0 ? round($montant / $total * 100, 1) : 0.0];
        }
        unset($montant);

        return $parMoyen;
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