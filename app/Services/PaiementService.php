<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\CommandeRepositoryInterface;
use App\Interfaces\PaiementRepositoryInterface;
use App\Models\Paiement;
use Exceptions\CommandeInexistanteException;
use Exceptions\MontantPaiementInvalideException;

class PaiementService
{
    public const IMPAYEE = 'IMPAYEE';
    public const PARTIELLEMENT_PAYEE = 'PARTIELLEMENT_PAYEE';
    public const PAYEE = 'PAYEE';

    public function __construct(
        private PaiementRepositoryInterface $paiements,
        private CommandeRepositoryInterface $commandes,
    ) {}

    public function enregistrerPaiement(int $commandeId, float $montant, string $moyen): Paiement
    {
        if ($montant <= 0) {
            throw new MontantPaiementInvalideException('Le montant doit etre positif.');
        }

        $commande = $this->commandes->findById($commandeId)
            ?? throw new CommandeInexistanteException("Aucune commande trouvee avec l'id $commandeId");

        $montantRestant = $commande->total - $this->paiements->sommePaiements($commandeId);

        if ($montant > $montantRestant) {
            throw new MontantPaiementInvalideException(
                sprintf('Le montant depasse le reste a payer (%.0f restant)', $montantRestant)
            );
        }

        return $this->paiements->create($commandeId, $montant, $moyen);
    }

    public function listerPaiementsCommande(int $commandeId): array
    {
        return $this->paiements->findByCommande($commandeId);
    }

    public function listerTousLesPaiements(): array
    {
        return $this->paiements->findAll();
    }

    public function montantRestant(int $commandeId): float
    {
        $commande = $this->commandes->findById($commandeId)
            ?? throw new CommandeInexistanteException("Aucune commande trouvee avec l'id $commandeId");
        return $commande->total - $this->paiements->sommePaiements($commandeId);
    }

    public function calculerStatutPaiement(int $commandeId): string
    {
        $commande = $this->commandes->findById($commandeId)
            ?? throw new CommandeInexistanteException("Aucune commande trouvee avec l'id $commandeId");
        $dejaPaye = $this->paiements->sommePaiements($commandeId);

        if ($dejaPaye <= 0) {
            return self::IMPAYEE;
        }
        return $dejaPaye < $commande->total ? self::PARTIELLEMENT_PAYEE : self::PAYEE;
    }
}