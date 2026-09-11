<?php
/** @var \App\Models\Commande $commande */
/** @var \App\Models\Facture|null $facture */

$statuts = [
    'EN_ATTENTE'      => 'En attente de confirmation',
    'EN_PREPARATION'  => 'En préparation',
    'PRETE'           => 'Prête au comptoir',
    'RETIREE'         => 'Commande retirée',
    'ANNULEE'         => 'Commande annulée',
];
?>

<div class="py-8 flex justify-center">
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- En-tête facture -->
        <div class="bg-gray-900 text-white px-8 py-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xl font-extrabold mb-4">
                    <span class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-utensils"></i>
                    </span>
                    Saveur <span class="text-red-400">221</span>
                </div>
                <h1 class="text-lg font-bold">Facture <?= htmlspecialchars((string) ($facture->numero ?? '')) ?></h1>
                <p class="text-sm text-gray-400 mt-1">Commande <?= htmlspecialchars($commande->numCommande) ?></p>
            </div>
            <div class="text-sm sm:text-right">
                <p class="text-gray-400">Date d'émission</p>
                <p class="font-semibold mt-1"><?= htmlspecialchars((string) ($facture->dateEmission ?? $commande->dateCommande)) ?></p>
                <p class="text-gray-400 mt-3">Statut</p>
                <p class="font-semibold mt-1"><?= htmlspecialchars($statuts[$commande->statut] ?? str_replace('_', ' ', $commande->statut)) ?></p>
            </div>
        </div>

        <!-- Détail ligne -->
        <div class="px-8 pt-8">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                            <th class="pb-3 font-semibold">Produit</th>
                            <th class="pb-3 font-semibold text-center">Qté</th>
                            <th class="pb-3 font-semibold text-right">Prix unitaire</th>
                            <th class="pb-3 font-semibold text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commande->lignes as $ligne): ?>
                        <tr class="border-b border-gray-50">
                            <td class="py-4 font-semibold text-gray-800"><?= htmlspecialchars((string) $ligne->produitLibelle) ?></td>
                            <td class="py-4 text-center text-gray-500"><?= $ligne->quantite ?></td>
                            <td class="py-4 text-right text-gray-500"><?= number_format($ligne->prixUnitaire, 0, ' ', ' ') ?> FCFA</td>
                            <td class="py-4 text-right font-semibold text-gray-800"><?= number_format($ligne->sousTotal, 0, ' ', ' ') ?> FCFA</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="flex items-end justify-between gap-4 my-8">
                <a href="/mes-commandes" class="px-5 py-3 rounded-lg bg-white border border-gray-200 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Retour à mes commandes
                </a>
                <div class="flex items-center gap-3">
                    <a href="/commandes/<?= $commande->id ?>/facture/pdf" class="px-5 py-3 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition flex items-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Télécharger la facture (PDF)
                    </a>
                    <div class="text-right">
                    <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">Total réglé</p>
                    <p class="text-3xl font-extrabold text-primary"><?= number_format($facture->montantTotal ?? $commande->total, 0, ' ', ' ') ?> FCFA</p>
                </div>
                </div>
            </div>
        </div>

        <!-- Pied de facture -->
        <div class="bg-gray-50 px-8 py-5 text-center text-xs text-gray-400">
            Route des Almadies, Dakar, Sénégal — +221 78 540 55 93 — Merci de votre confiance !
        </div>
    </div>
</div>
