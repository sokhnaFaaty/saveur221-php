<?php
/** @var \App\Models\Recu $recu */
/** @var \App\Models\Paiement $paiement */
/** @var \App\Models\Commande $commande */

$moyens = [
    'WAVE'         => 'Wave',
    'ORANGE_MONEY' => 'Orange Money',
    'ESPECES'      => 'Espèces',
];
$libelleMoyen = $moyens[$paiement->moyen] ?? $paiement->moyen;
?>

<div class="py-8 flex justify-center">
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- En-tête reçu -->
        <div class="bg-green-700 text-white px-8 py-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xl font-extrabold mb-4">
                    <span class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-utensils"></i>
                    </span>
                    Saveur <span class="text-red-300">221</span>
                </div>
                <p class="text-xs font-bold uppercase tracking-widest text-green-200 mb-1"><i class="fa-solid fa-circle-check"></i> Paiement encaissé</p>
                <h1 class="text-lg font-bold">Reçu <?= htmlspecialchars($recu->numero) ?></h1>
                <p class="text-sm text-green-200 mt-1">Commande <?= htmlspecialchars($commande->numCommande) ?></p>
            </div>
            <div class="text-sm sm:text-right">
                <p class="text-green-200">Date d'émission</p>
                <p class="font-semibold mt-1"><?= date('d/m/Y à H\hi', strtotime($recu->dateEmission)) ?></p>
            </div>
        </div>

        <!-- Détail du paiement -->
        <div class="px-8 pt-8">
            <div class="grid sm:grid-cols-2 gap-4 mb-8">
                <div class="bg-gray-50 rounded-xl px-4 py-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">Mode de paiement</p>
                    <p class="font-bold text-gray-800"><?= htmlspecialchars($libelleMoyen) ?></p>
                </div>
                <div class="bg-gray-50 rounded-xl px-4 py-4">
                    <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">Date du paiement</p>
                    <p class="font-bold text-gray-800"><?= date('d/m/Y à H\hi', strtotime($paiement->datePaiement)) ?></p>
                </div>
            </div>

            <!-- Ligne de la commande -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                            <th class="pb-3 font-semibold">Produit</th>
                            <th class="pb-3 font-semibold text-center">Qté</th>
                            <th class="pb-3 font-semibold text-right">Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($commande->lignes as $ligne): ?>
                        <tr class="border-b border-gray-50">
                            <td class="py-4 font-semibold text-gray-800"><?= htmlspecialchars((string) $ligne->produitLibelle) ?></td>
                            <td class="py-4 text-center text-gray-500"><?= $ligne->quantite ?></td>
                            <td class="py-4 text-right font-semibold text-gray-800"><?= number_format($ligne->sousTotal, 0, ' ', ' ') ?> FCFA</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="flex items-center justify-between border-t-2 border-dashed border-gray-200 py-4 mt-4">
                <p class="text-sm uppercase tracking-wide text-gray-400 font-semibold">Total facturé</p>
                <p class="text-xl font-extrabold text-gray-900"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</p>
            </div>
            <div class="flex items-center justify-between py-2">
                <p class="text-sm uppercase tracking-wide text-gray-400 font-semibold">Montant réglé</p>
                <p class="text-3xl font-extrabold text-green-600"><?= number_format($paiement->montant, 0, ' ', ' ') ?> FCFA</p>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap items-center justify-between gap-4 my-8">
                <a href="/mes-commandes" class="px-5 py-3 rounded-lg bg-white border border-gray-200 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Retour à mes commandes
                </a>
                <a href="/commandes/<?= $commande->id ?>/facture" class="px-5 py-3 rounded-lg bg-white border border-gray-200 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center gap-2">
                    <i class="fa-regular fa-file-lines"></i> Voir la facture
                </a>
            </div>
        </div>

        <!-- Pied -->
        <div class="bg-gray-50 px-8 py-5 text-center text-xs text-gray-400">
            Route des Almadies, Dakar, Sénégal — +221 78 540 55 93 — Merci de votre confiance !
        </div>
    </div>
</div>