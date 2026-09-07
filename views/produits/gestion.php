<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Gestion des Menus & Plats</h1>
        <p class="text-sm text-gray-500">Creez, modifiez les tarifs, stocks, temps de preparation et visuels.</p>
    </div>
    <a href="/produits/creer" class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Ajouter un nouveau plat
    </a>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <form method="get" action="/produits" class="flex-1 min-w-[240px]">
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher un plat, ingredient"
               class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
    </form>
    <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
        <a href="?<?= http_build_query(array_merge($_GET, ['vue' => 'tableau'])) ?>"
           class="px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-2 <?= $vue === 'tableau' ? 'bg-white shadow-sm' : 'text-gray-500' ?>">
            <i class="fa-solid fa-list"></i> Tableau
        </a>
        <a href="?<?= http_build_query(array_merge($_GET, ['vue' => 'cartes'])) ?>"
           class="px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-2 <?= $vue === 'cartes' ? 'bg-white shadow-sm' : 'text-gray-500' ?>">
            <i class="fa-solid fa-table-cells-large"></i> Cartes
        </a>
    </div>
</div>

<?php if ($vue === 'tableau'): ?>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Plat</th><th class="px-4 py-3">Categorie</th><th class="px-4 py-3">Prix</th>
                <th class="px-4 py-3">Stock</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach ($produits as $plat): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 flex items-center gap-3">
                    <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-10 h-10 rounded-lg object-cover">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($plat->libelle) ?></p>
                        <?php if ($plat->tempsPreparation): ?><p class="text-xs text-gray-400">~<?= $plat->tempsPreparation ?> min prep</p><?php endif; ?>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></td>
                <td class="px-4 py-3 font-bold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</td>
                <td class="px-4 py-3"><?= $plat->quantiteStock ?> portion(s)</td>
                <td class="px-4 py-3">
                    <?php if ($plat->estEnRupture()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-50 text-red-700">Rupture</span>
                    <?php elseif ($plat->stockFaible()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-orange-50 text-orange-700">Stock faible</span>
                    <?php else: ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-50 text-green-700">En stock</span><?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="/produits/<?= $plat->id ?>/modifier" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition"><i class="fa-regular fa-pen-to-square text-xs"></i></a>
                        <button type="button" onclick='demanderConfirmation({titre:"Supprimer ce plat",message:"Cette operation est irreversible.",cible:<?= json_encode($plat->libelle) ?>,actionUrl:"/produits/<?= $plat->id ?>/delete"})' class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if ($produits === []): ?><tr><td colspan="6" class="text-center text-gray-400 py-10">Aucun plat trouve.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($produits as $plat): ?>
    <div class="bg-white rounded-xl border-t-4 border-primary shadow-sm hover:shadow-md transition overflow-hidden">
        <div class="relative h-36">
            <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($plat->libelle) ?>">
            <?php if ($plat->estEnRupture()): ?><span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-red-50 text-red-700">Rupture</span>
            <?php elseif ($plat->stockFaible()): ?><span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-orange-50 text-orange-700">Stock faible</span>
            <?php else: ?><span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-green-50 text-green-700">En stock</span><?php endif; ?>
        </div>
        <div class="p-4">
            <h3 class="font-bold mb-1"><?= htmlspecialchars($plat->libelle) ?></h3>
            <p class="text-sm text-gray-500 mb-3"><?= htmlspecialchars((string) ($plat->description ?: '-')) ?></p>
            <div class="flex items-center justify-between mb-3">
                <span class="font-extrabold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</span>
                <span class="text-xs text-gray-400"><?= $plat->quantiteStock ?> portion(s)</span>
            </div>
            <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
                <a href="/produits/<?= $plat->id ?>/modifier" class="flex-1 text-center py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm font-semibold transition"><i class="fa-regular fa-pen-to-square"></i></a>
                <button type="button" onclick='demanderConfirmation({titre:"Supprimer ce plat",message:"Cette operation est irreversible.",cible:<?= json_encode($plat->libelle) ?>,actionUrl:"/produits/<?= $plat->id ?>/delete"})' class="flex-1 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-sm font-semibold transition text-red-600"><i class="fa-regular fa-trash-can"></i></button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if ($produits === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucun plat trouve.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>