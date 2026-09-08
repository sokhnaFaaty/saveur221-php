<?php /** @var float $total @var int $totalTransactions */
$couleursStatut = [
    \App\Services\PaiementService::IMPAYEE => 'bg-red-50 text-red-600',
    \App\Services\PaiementService::PARTIELLEMENT_PAYEE => 'bg-orange-50 text-orange-600',
    \App\Services\PaiementService::PAYEE => 'bg-emerald-50 text-emerald-600',
];
$libelleStatut = [
    \App\Services\PaiementService::IMPAYEE => 'Impayee',
    \App\Services\PaiementService::PARTIELLEMENT_PAYEE => 'Partiellement payee',
    \App\Services\PaiementService::PAYEE => 'Payee',
];
?>
<h1 class="text-2xl font-extrabold mb-1">Caisse & Enregistrements des Règlements</h1>
<p class="text-sm text-gray-500 mb-6">Journal des transactions Wave, Orange Money et Espèces perçues au comptoir.</p>

<div class="bg-white rounded-xl p-5 shadow-sm mb-6 flex items-center justify-between max-w-sm">
    <div class="flex items-center gap-3">
        <span class="w-10 h-10 rounded-lg bg-green-50 text-green-600 flex items-center justify-center"><i class="fa-solid fa-dollar-sign"></i></span>
        <div>
            <p class="text-xs text-gray-500 font-bold">TOTAL ENCAISSÉ</p>
            <p class="font-extrabold text-lg text-green-600"><?= number_format($total, 0) ?> FCFA</p>
        </div>
    </div>
    <span class="text-xs bg-gray-100 px-3 py-1 rounded-full font-semibold"><?= $totalTransactions ?> transaction(s)</span>
</div>

<div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1 mb-6 w-fit">
    <a href="?<?= http_build_query(array_merge($_GET, ['vue' => 'tableau'])) ?>"
       class="px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-2 <?= $vue === 'tableau' ? 'bg-white shadow-sm' : 'text-gray-500' ?>">
        <i class="fa-solid fa-list"></i> Tableau
    </a>
    <a href="?<?= http_build_query(array_merge($_GET, ['vue' => 'cartes'])) ?>"
       class="px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-2 <?= $vue === 'cartes' ? 'bg-white shadow-sm' : 'text-gray-500' ?>">
        <i class="fa-solid fa-table-cells-large"></i> Cartes
    </a>
</div>

<?php if ($vue === 'tableau'): ?>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Commande</th><th class="px-4 py-3">Date/Heure</th><th class="px-4 py-3">Moyen</th><th class="px-4 py-3">Montant</th><th class="px-4 py-3">Statut</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($paiements as $p): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3"><a href="/commandes/<?= $p->commandeId ?>" class="font-semibold text-primary hover:underline">#<?= $p->commandeId ?></a></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($p->datePaiement) ?></td>
                <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100"><?= htmlspecialchars($p->moyen) ?></span></td>
                <td class="px-4 py-3 font-bold text-green-600"><?= number_format($p->montant, 0) ?> FCFA</td>
                <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full <?= $couleursStatut[$statutParCommande[$p->commandeId] ?? \App\Services\PaiementService::IMPAYEE] ?? 'bg-gray-100 text-gray-500' ?>"><?= $libelleStatut[$statutParCommande[$p->commandeId] ?? \App\Services\PaiementService::IMPAYEE] ?? 'Impayee' ?></span></td>
            </tr>
        <?php endforeach; ?>
        <?php if ($paiements === []): ?><tr><td colspan="5" class="text-center text-gray-400 py-10">Aucune transaction.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($paiements as $p): ?>
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center justify-between mb-3">
            <a href="/commandes/<?= $p->commandeId ?>" class="font-bold text-primary hover:underline">Commande #<?= $p->commandeId ?></a>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-gray-100"><?= htmlspecialchars($p->moyen) ?></span>
                <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $couleursStatut[$statutParCommande[$p->commandeId] ?? \App\Services\PaiementService::IMPAYEE] ?? 'bg-gray-100 text-gray-500' ?>"><?= $libelleStatut[$statutParCommande[$p->commandeId] ?? \App\Services\PaiementService::IMPAYEE] ?? 'Impayee' ?></span>
            </div>
        </div>
        <p class="text-xs text-gray-400 mb-3"><?= htmlspecialchars($p->datePaiement) ?></p>
        <p class="font-extrabold text-lg text-green-600"><?= number_format($p->montant, 0) ?> FCFA</p>
    </div>
    <?php endforeach; ?>
    <?php if ($paiements === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucune transaction.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>