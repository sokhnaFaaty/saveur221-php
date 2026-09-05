<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\CommandeRepositoryInterface;
use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Commande;
use App\Models\LigneCommande;
use Core\Database;
use Exceptions\ProduitInexistantException;
use Exceptions\ValidationException;

class CommandeRepository implements CommandeRepositoryInterface
{
    private const SELECT_LIGNES =
        'SELECT lc.*, p.libelle AS produit_libelle FROM ligne_commandes lc
         JOIN produits p ON p.id = lc.produit_id WHERE lc.commande_id = ?';

    public function __construct(private ProduitRepositoryInterface $produits) {}

    public function findById(int $id): ?Commande
    {
        $rows = Database::executeSelect('SELECT * FROM commandes WHERE id = ?', [$id]);
        if ($rows === []) {
            return null;
        }
        $lignes = array_map(LigneCommande::fromRow(...), Database::executeSelect(self::SELECT_LIGNES, [$id]));
        return Commande::fromRow($rows[0], $lignes);
    }

    public function findByClient(int $clientId): array
    {
        $sql = 'SELECT * FROM commandes WHERE client_id = ? ORDER BY date_commande DESC';
        return $this->hydraterAvecLignes(Database::executeSelect($sql, [$clientId]));
    }

    public function findAll(): array
    {
        $sql = 'SELECT * FROM commandes ORDER BY date_commande DESC';
        return $this->hydraterAvecLignes(Database::executeSelect($sql));
    }

    public function findByStatut(string $statut): array
    {
        $sql = 'SELECT * FROM commandes WHERE statut = ? ORDER BY date_commande DESC';
        return $this->hydraterAvecLignes(Database::executeSelect($sql, [$statut]));
    }

    public function create(int $clientId, array $lignesPanier): Commande
    {
        if ($lignesPanier === []) {
            throw new ValidationException('Le panier est vide.');
        }

        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            // 1. On cree la commande avec un numero provisoire (unique),
            //    car on a besoin de l'id auto-genere pour construire le vrai numero.
            $rows = Database::executeSelect(
                'INSERT INTO commandes (num_commande, total, statut, client_id)
                 VALUES (?, 0, ?, ?) RETURNING id',
                ['TEMP-' . uniqid(), Commande::EN_ATTENTE, $clientId]
            );
            $commandeId = (int) $rows[0]->id;

            // 2. Le vrai numero, maintenant qu'on connait l'id : CMD-2026-00123
            $numCommande = sprintf('CMD-%s-%05d', date('Y'), $commandeId);
            Database::executeUpdate('UPDATE commandes SET num_commande = ? WHERE id = ?', [$numCommande, $commandeId]);

            // 3. Chaque ligne : on va chercher le PRIX EN BASE (jamais celui envoye par le client,
            //    facilement falsifiable depuis le navigateur), on diminue le stock, on insere la ligne.
            $total = 0.0;
            foreach ($lignesPanier as $item) {
                $produit = $this->produits->findById((int) $item['produit_id']);
                if ($produit === null) {
                    throw new ProduitInexistantException("Produit introuvable : {$item['produit_id']}");
                }

                $quantite = (int) $item['quantite'];
                $this->produits->diminuerStock($produit->id, $quantite); // leve StockInsuffisantException si besoin

                $sousTotal = $produit->prix * $quantite;
                $total += $sousTotal;

                Database::executeUpdate(
                    'INSERT INTO ligne_commandes (quantite, prix_unitaire, sous_total, produit_id, commande_id, instructions_speciales)
                     VALUES (?, ?, ?, ?, ?, ?)',
                    [$quantite, $produit->prix, $sousTotal, $produit->id, $commandeId, $item['instructions'] ?? null]
                );
            }

            Database::executeUpdate('UPDATE commandes SET total = ? WHERE id = ?', [$total, $commandeId]);

            $pdo->commit();
            return $this->findById($commandeId);
        } catch (\Throwable $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function updateStatut(int $id, string $statut): void
    {
        Database::executeUpdate('UPDATE commandes SET statut = ? WHERE id = ?', [$statut, $id]);
    }

    /** @param object[] $rows */
    private function hydraterAvecLignes(array $rows): array
    {
        return array_map(function (object $row) {
            $lignes = array_map(LigneCommande::fromRow(...), Database::executeSelect(self::SELECT_LIGNES, [$row->id]));
            return Commande::fromRow($row, $lignes);
        }, $rows);
    }
}