<?php
$couleursStatut = [
    'EN_ATTENTE' => 'bg-amber-50 text-amber-700', 'EN_PREPARATION' => 'bg-blue-50 text-blue-700',
    'PRETE' => 'bg-green-50 text-green-700', 'RETIREE' => 'bg-gray-100 text-gray-500', 'ANNULEE' => 'bg-red-50 text-red-700',
];
$prochainStatut = ['EN_ATTENTE' => 'EN_PREPARATION', 'EN_PREPARATION' => 'PRETE', 'PRETE' => 'RETIREE'];
$libelleAction = ['EN_ATTENTE' => 'Lancer en Cuisine', 'EN_PREPARATION' => 'Marquer Prete au Comptoir', 'PRETE' => 'Marquer Retiree'];

$couleursPaiement = [
    \App\Services\PaiementService::IMPAYEE => 'bg-red-50 text-red-600',
    \App\Services\PaiementService::PARTIELLEMENT_PAYEE => 'bg-orange-50 text-orange-600',
    \App\Services\PaiementService::PAYEE => 'bg-emerald-50 text-emerald-600',
];
$libellePaiement = [
    \App\Services\PaiementService::IMPAYEE => 'Impayee',
    \App\Services\PaiementService::PARTIELLEMENT_PAYEE => 'Partiellement payee',
    \App\Services\PaiementService::PAYEE => 'Payee',
];

function encaissementForm(\App\Models\Commande $commande, float $reste): string
{
    if ($commande->statut === 'ANNULEE' || $reste <= 0) {
        return '';
    }
    return sprintf(
        '<form method="post" action="/commandes/%d/paiements" class="flex flex-wrap items-center gap-1.5 mt-2">
            <input type="number" name="montant" min="1" max="%d" step="any" placeholder="Montant"
                   class="w-24 px-2 py-1.5 rounded-lg border border-gray-200 text-xs text-center" title="Montant encaisse">
            <select name="moyen" class="px-2 py-1.5 rounded-lg border border-gray-200 text-xs bg-white">
                <option value="WAVE">Wave</option>
                <option value="ORANGE_MONEY">Orange Money</option>
                <option value="ESPECES">Especes</option>
            </select>
            <button class="px-3 py-1.5 rounded-lg text-white text-xs font-semibold hover:opacity-90 transition" style="background-color:#BF360C">Encaisser</button>
        </form>',
        $commande->id, (int) $reste
    );
}
?>
<h1 class="text-2xl font-extrabold mb-1">Gestion des Commandes Clients</h1>
<p class="text-sm text-gray-500 mb-6">Suivi des statuts (En attente &rarr; En préparation &rarr; Prête &rarr; Retirée) et encaissement au comptoir.</p>

<form method="get" action="/commandes" class="flex flex-wrap gap-2 mb-4">
    <a href="/commandes" class="px-4 py-2 rounded-lg text-sm font-semibold <?= !$statutFiltre && !$paiementFiltre ? 'bg-[#B83518] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#B83518] hover:text-[#B83518] transition' ?>">Tous</a>
    <?php foreach (['EN_ATTENTE', 'EN_PREPARATION', 'PRETE', 'RETIREE', 'ANNULEE'] as $s): ?>
    <a href="/commandes?statut=<?= $s ?><?= $paiementFiltre ? '&paiement=' . urlencode($paiementFiltre) : '' ?><?= $terme ? '&q=' . urlencode($terme) : '' ?>" class="px-4 py-2 rounded-lg text-sm font-semibold <?= $statutFiltre === $s ? 'bg-[#B83518] text-white shadow-sm' : 'bg-white border border-gray-200 text-gray-600 hover:border-[#B83518] hover:text-[#B83518] transition' ?>"><?= $s ?></a>
    <?php endforeach; ?>
</form>

<form method="get" action="/commandes" class="flex flex-wrap items-center gap-2 mb-6">
    <div class="relative flex-1 min-w-[220px]">
        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
        <input type="text" name="q" value="<?= htmlspecialchars($terme) ?>" placeholder="Rechercher une commande (n&deg;, date)..."
               class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-white border border-gray-200 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary">
        <?php if ($statutFiltre): ?><input type="hidden" name="statut" value="<?= htmlspecialchars((string) $statutFiltre) ?>"><?php endif; ?>
        <?php if ($paiementFiltre): ?><input type="hidden" name="paiement" value="<?= htmlspecialchars($paiementFiltre) ?>"><?php endif; ?>
    </div>
    <div class="relative">
        <select name="paiement" onchange="this.form.submit()"
                class="appearance-none w-full min-w-[200px] px-4 py-2.5 pr-9 rounded-xl bg-white border border-gray-200 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
            <option value="">Tous les paiements</option>
            <option value="IMPAYEE" <?= $paiementFiltre === 'IMPAYEE' ? 'selected' : '' ?>>Impayees</option>
            <option value="PARTIELLEMENT_PAYEE" <?= $paiementFiltre === 'PARTIELLEMENT_PAYEE' ? 'selected' : '' ?>>Partiellement payees</option>
            <option value="PAYEE" <?= $paiementFiltre === 'PAYEE' ? 'selected' : '' ?>>Payees</option>
        </select>
        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
    </div>
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
                <th class="px-4 py-3">N&deg; Commande</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Statut</th>
                <th class="px-4 py-3">Total</th><th class="px-4 py-3">Paiement</th><th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($commandes as $commande): $reste = (float) ($resteParCommande[$commande->id] ?? 0); $prochain = $prochainStatut[$commande->statut] ?? null; ?>
            <tr class="hover:bg-gray-50 <?= $commande->statut === 'PRETE' ? 'bg-green-50/40' : '' ?>">
                <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($commande->numCommande) ?></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($commande->dateCommande) ?></td>
                <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $couleursStatut[$commande->statut] ?? '' ?>"><?= str_replace('_', ' ', $commande->statut) ?></span></td>
                <td class="px-4 py-3 font-bold text-primary"><?= number_format($commande->total, 0) ?> FCFA</td>
                <td class="px-4 py-3">
                    <div class="space-y-1">
                        <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-full <?= $couleursPaiement[$statutPaiementParCommande[$commande->id] ?? \App\Services\PaiementService::IMPAYEE] ?? 'bg-gray-100 text-gray-500' ?>"><?= $libellePaiement[$statutPaiementParCommande[$commande->id] ?? \App\Services\PaiementService::IMPAYEE] ?? 'Impayee' ?></span>
                        <?php if ($reste > 0): ?><p class="text-xs text-orange-600 font-semibold">Reste: <?= number_format($reste, 0) ?> FCFA</p><?php endif; ?>
                        <?php foreach ($paiementsParCommande[$commande->id] ?? [] as $paiement): ?>
                        <div class="flex items-center gap-1.5 text-xs text-gray-500">
                            <span><?= number_format($paiement->montant, 0) ?> (<?= htmlspecialchars($paiement->moyen) ?>)</span>
                            <?php if (isset($recusParPaiement[$paiement->id])): ?>
                            <a href="/recus/<?= $paiement->id ?>" class="text-[#B83518] hover:underline" title="Voir le recu"><i class="fa-regular fa-receipt"></i></a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                        <?= encaissementForm($commande, $reste) ?>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <?php if ($prochain !== null): ?>
                        <?php if ($prochain === 'RETIREE' && $reste > 0): ?>
                        <button type="button" disabled title="Paiement incomplet" class="px-3 py-1.5 rounded-lg bg-gray-200 text-gray-400 text-xs font-semibold cursor-not-allowed"><?= $libelleAction[$commande->statut] ?></button>
                        <?php else: ?>
                        <form method="post" action="/commandes/<?= $commande->id ?>/statut">
                            <input type="hidden" name="statut" value="<?= $prochain ?>">
                            <button class="px-3 py-1.5 rounded-lg bg-[#B83518] text-white text-xs font-semibold hover:bg-primary-dark transition"><?= $libelleAction[$commande->statut] ?></button>
                        </form>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!in_array($commande->statut, ['RETIREE', 'ANNULEE'], true)): ?>
                        <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Annuler la commande",message:"L annulation restaure le stock et est irreversible.",cible:' . json_encode($commande->numCommande) . ',actionUrl:"/commandes/' . $commande->id . '/annuler"})', ENT_QUOTES, 'UTF-8') ?>" class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition">Annuler</button>
                        <?php endif; ?>
                        <a href="/commandes/<?= $commande->id ?>/facture" class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-gray-600 text-xs font-semibold hover:border-[#B83518] hover:text-[#B83518] transition"><i class="fa-regular fa-file-lines"></i> Facture</a>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($commandes === []): ?><tr><td colspan="6" class="text-center text-gray-400 py-10">Aucune commande.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="space-y-4">
    <?php foreach ($commandes as $commande): $reste = (float) ($resteParCommande[$commande->id] ?? 0); $prochain = $prochainStatut[$commande->statut] ?? null; ?>
    <div class="<?= $commande->statut === 'PRETE' ? 'bg-green-50/40' : 'bg-white' ?> rounded-xl p-5 shadow-sm">
        <div class="flex items-start justify-between mb-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="font-bold"><?= htmlspecialchars($commande->numCommande) ?></h3>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $couleursStatut[$commande->statut] ?? '' ?>"><?= str_replace('_', ' ', $commande->statut) ?></span>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?= $couleursPaiement[$statutPaiementParCommande[$commande->id] ?? \App\Services\PaiementService::IMPAYEE] ?? 'bg-gray-100 text-gray-500' ?>"><?= $libellePaiement[$statutPaiementParCommande[$commande->id] ?? \App\Services\PaiementService::IMPAYEE] ?? 'Impayee' ?></span>
                </div>
                <p class="text-xs text-gray-400"><?= htmlspecialchars($commande->dateCommande) ?></p>
            </div>
            <div class="text-right">
                <p class="font-extrabold text-primary"><?= number_format($commande->total, 0) ?> FCFA</p>
                <?php if ($reste > 0): ?><p class="text-xs text-orange-600 font-semibold">Reste a payer: <?= number_format($reste, 0) ?> FCFA</p><?php endif; ?>
            </div>
        </div>

        <div class="bg-white/70 rounded-lg p-3 mb-3 text-sm">
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

        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <?php if ($prochain !== null): ?>
                <?php if ($prochain === 'RETIREE' && $reste > 0): ?>
                <button type="button" disabled title="Paiement incomplet" class="px-4 py-2 rounded-lg bg-gray-200 text-gray-400 text-xs font-semibold cursor-not-allowed"><?= $libelleAction[$commande->statut] ?></button>
                <?php else: ?>
                <form method="post" action="/commandes/<?= $commande->id ?>/statut">
                    <input type="hidden" name="statut" value="<?= $prochain ?>">
                    <button class="px-4 py-2 rounded-lg bg-[#B83518] text-white text-xs font-semibold hover:bg-primary-dark transition">
                        <?= $libelleAction[$commande->statut] ?>
                    </button>
                </form>
                <?php endif; ?>
                <?php endif; ?>
                <?php if (!in_array($commande->statut, ['RETIREE', 'ANNULEE'], true)): ?>
                <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Annuler la commande",message:"L annulation restaure le stock et est irreversible.",cible:' . json_encode($commande->numCommande) . ',actionUrl:"/commandes/' . $commande->id . '/annuler"})', ENT_QUOTES, 'UTF-8') ?>" class="px-4 py-2 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition">Annuler</button>
                <?php endif; ?>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <?php foreach ($paiementsParCommande[$commande->id] ?? [] as $paiement): ?>
                <?php if (isset($recusParPaiement[$paiement->id])): ?>
                <a href="/recus/<?= $paiement->id ?>" class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-600 text-xs font-semibold hover:border-[#B83518] hover:text-[#B83518] transition"><i class="fa-regular fa-receipt"></i> Recu (<?= number_format($paiement->montant, 0) ?>)</a>
                <?php endif; ?>
                <?php endforeach; ?>
                <a href="/commandes/<?= $commande->id ?>/facture" class="px-3 py-2 rounded-lg bg-white border border-gray-300 text-gray-600 text-xs font-semibold hover:border-[#B83518] hover:text-[#B83518] transition"><i class="fa-regular fa-file-lines"></i> Facture</a>
            </div>
        </div>

        <?= encaissementForm($commande, $reste) ?>
    </div>
    <?php endforeach; ?>
    <?php if ($commandes === []): ?><p class="text-center text-gray-400 py-16">Aucune commande.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>