<?php $total = array_sum(array_map(fn ($p) => $p->montant, $paiements)); ?>
<h1 class="text-2xl font-extrabold mb-1">Caisse & Enregistrements des Reglements</h1>
<p class="text-sm text-gray-500 mb-6">Journal des transactions Wave, Orange Money et Especes percues au comptoir.</p>

<div class="bg-white rounded-xl p-5 shadow-sm mb-6 flex items-center justify-between max-w-sm">
    <div class="flex items-center gap-3">
        <span class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-dollar-sign"></i></span>
        <div>
            <p class="text-xs text-gray-500 font-bold">TOTAL ENCAISSE</p>
            <p class="font-extrabold text-lg text-green-600"><?= number_format($total, 0) ?> FCFA</p>
        </div>
    </div>
    <span class="text-xs bg-gray-100 px-3 py-1 rounded-full font-semibold"><?= count($paiements) ?> transaction(s)</span>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Commande</th><th class="px-4 py-3">Date/Heure</th><th class="px-4 py-3">Moyen</th><th class="px-4 py-3">Montant</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($paiements as $p): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3"><a href="/commandes/<?= $p->commandeId ?>" class="font-semibold text-primary hover:underline">#<?= $p->commandeId ?></a></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($p->datePaiement) ?></td>
                <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100"><?= htmlspecialchars($p->moyen) ?></span></td>
                <td class="px-4 py-3 font-bold text-green-600"><?= number_format($p->montant, 0) ?> FCFA</td>
            </tr>
        <?php endforeach; ?>
        <?php if ($paiements === []): ?><tr><td colspan="4" class="text-center text-gray-400 py-10">Aucune transaction.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>