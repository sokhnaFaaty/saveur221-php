<?php
$couleursStatut = [
    'EN_ATTENTE' => 'bg-amber-50 text-amber-700', 'EN_PREPARATION' => 'bg-blue-50 text-blue-700',
    'PRETE' => 'bg-green-50 text-green-700', 'RETIREE' => 'bg-gray-100 text-gray-500', 'ANNULEE' => 'bg-red-50 text-red-700',
];
$prochainStatut = ['EN_ATTENTE' => 'EN_PREPARATION', 'EN_PREPARATION' => 'PRETE', 'PRETE' => 'RETIREE'];
$libelleAction = ['EN_ATTENTE' => 'Lancer en Cuisine', 'EN_PREPARATION' => 'Marquer Prete au Comptoir', 'PRETE' => 'Marquer Retiree'];
?>
<h1 class="text-2xl font-extrabold mb-1">Gestion des Commandes Clients</h1>
<p class="text-sm text-gray-500 mb-6">Suivi des statuts (En attente &rarr; En préparation &rarr; Prête &rarr; Retirée) et gestion du comptoir.</p>

<form method="get" action="/commandes" class="flex flex-wrap gap-2 mb-6">
    <a href="/commandes" class="px-4 py-2 rounded-lg text-sm font-semibold <?= !$statutFiltre ? 'bg-primary text-white' : 'bg-white border border-gray-200' ?>">Tous</a>
    <?php foreach (['EN_ATTENTE', 'EN_PREPARATION', 'PRETE', 'RETIREE', 'ANNULEE'] as $s): ?>
    <a href="/commandes?statut=<?= $s ?>" class="px-4 py-2 rounded-lg text-sm font-semibold <?= $statutFiltre === $s ? 'bg-primary text-white' : 'bg-white border border-gray-200' ?>"><?= $s ?></a>
    <?php endforeach; ?>
</form>

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
            <tr>
                <th class="px-4 py-3">N&deg; Commande</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Total</th><th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($commandes as $commande): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($commande->numCommande) ?></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($commande->dateCommande) ?></td>
                <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $couleursStatut[$commande->statut] ?? '' ?>"><?= str_replace('_', ' ', $commande->statut) ?></span></td>
                <td class="px-4 py-3 font-bold text-primary"><?= number_format($commande->total, 0) ?> FCFA</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <?php if (isset($prochainStatut[$commande->statut])): ?>
                        <form method="post" action="/commandes/<?= $commande->id ?>/statut">
                            <input type="hidden" name="statut" value="<?= $prochainStatut[$commande->statut] ?>">
                            <button class="px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition"><?= $libelleAction[$commande->statut] ?></button>
                        </form>
                        <?php endif; ?>
                        <?php if (!in_array($commande->statut, ['RETIREE', 'ANNULEE'], true)): ?>
                        <button type="button" onclick='demanderConfirmation({titre:"Annuler la commande",message:"L annulation restaure le stock et est irreversible.",cible:<?= json_encode($commande->numCommande) ?>,actionUrl:"/commandes/<?= $commande->id ?>/annuler"})' class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition">Annuler</button>
                        <?php endif; ?>
                        <a href="/commandes/<?= $commande->id ?>/facture" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold hover:border-primary transition"><i class="fa-regular fa-file-lines"></i> Facture</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($commandes === []): ?><tr><td colspan="5" class="text-center text-gray-400 py-10">Aucune commande.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="space-y-4">
    <?php foreach ($commandes as $commande): ?>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold"><?= htmlspecialchars($commande->numCommande) ?></h3>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $couleursStatut[$commande->statut] ?? '' ?>"><?= str_replace('_', ' ', $commande->statut) ?></span>
                </div>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($commande->dateCommande) ?></p>
            </div>
            <p class="font-extrabold text-primary"><?= number_format($commande->total, 0) ?> FCFA</p>
        </div>

        <div class="bg-gray-50 rounded-lg p-3 mb-3 text-sm">
            <?php foreach ($commande->lignes as $ligne): ?>
            <div class="flex justify-between py-1">
                <span><?= $ligne->quantite ?>x <?= htmlspecialchars((string) $ligne->produitLibelle) ?></span>
                <span class="text-gray-500"><?= number_format($ligne->sousTotal, 0) ?> FCFA</span>
            </div>
            <?php if ($ligne->instructionsSpeciales): ?>
                <p class="text-xs text-primary italic">Note: <?= htmlspecialchars($ligne->instructionsSpeciales) ?></p>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <div class="flex items-center justify-between flex-wrap gap-2">
            <div class="flex items-center gap-2">
                <?php if (isset($prochainStatut[$commande->statut])): ?>
                <form method="post" action="/commandes/<?= $commande->id ?>/statut">
                    <input type="hidden" name="statut" value="<?= $prochainStatut[$commande->statut] ?>">
                    <button class="px-4 py-2 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                        <?= $libelleAction[$commande->statut] ?>
                    </button>
                </form>
                <?php endif; ?>
                <?php if (!in_array($commande->statut, ['RETIREE', 'ANNULEE'], true)): ?>
                <button type="button" onclick='demanderConfirmation({titre:"Annuler la commande",message:"L annulation restaure le stock et est irreversible.",cible:<?= json_encode($commande->numCommande) ?>,actionUrl:"/commandes/<?= $commande->id ?>/annuler"})' class="px-4 py-2 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition">Annuler</button>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-2">
                <a href="/commandes/<?= $commande->id ?>/facture" class="px-3 py-2 rounded-lg border border-gray-200 text-xs font-semibold hover:border-primary transition"><i class="fa-regular fa-file-lines"></i> Facture</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if ($commandes === []): ?><p class="text-center text-gray-400 py-16">Aucune commande.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>