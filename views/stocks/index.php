<h1 class="text-2xl font-extrabold mb-1">Gestion & Reapprovisionnement des Stocks</h1>
<p class="text-sm text-gray-500 mb-6">Suivez les seuils d'alerte, reapprovisionnez les portions cuisinees.</p>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <form method="get" action="/stocks" class="flex-1 min-w-[240px] flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
            <input type="text" name="q" value="<?= htmlspecialchars($terme) ?>" placeholder="Rechercher un plat"
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-50 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="relative">
            <select name="categorie" onchange="this.form.submit()"
                    class="appearance-none w-full min-w-[220px] px-4 py-2.5 pr-9 rounded-xl bg-gray-50 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                <option value="">Toutes les categories</option>
                <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat->id ?>" <?= ($categorieId ?? null) === $cat->id ? 'selected' : '' ?>><?= htmlspecialchars($cat->libelle) ?></option>
                <?php endforeach; ?>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        <div class="flex items-center gap-2 text-sm font-semibold text-gray-500">
            <span>Mode d'affichage :</span>
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
    </form>
</div>

<?php if ($vue === 'tableau'): ?>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Plat</th><th class="px-4 py-3">Categorie</th><th class="px-4 py-3">Stock</th>
                <th class="px-4 py-3">Seuil</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach ($produits as $plat): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 flex items-center gap-3">
                    <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-10 h-10 rounded-lg object-cover" alt="<?= htmlspecialchars($plat->libelle) ?>">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($plat->libelle) ?></p>
                        <?php if ($plat->categorieLibelle !== null && $plat->categorieLibelle !== ''): ?><p class="text-xs text-gray-400"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></p><?php endif; ?>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></td>
                <td class="px-4 py-3"><?= $plat->quantiteStock ?> portion(s)</td>
                <td class="px-4 py-3 text-gray-500"><?= $plat->seuilAlerte ?> portion(s)</td>
                <td class="px-4 py-3">
                    <?php if ($plat->estEnRupture()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-50 text-red-700">Rupture</span>
                    <?php elseif ($plat->stockFaible()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-orange-50 text-orange-700">Faible</span>
                    <?php else: ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100">Optimal</span><?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <form method="post" action="/stocks/<?= $plat->id ?>/approvisionner">
                        <input type="hidden" name="quantite" value="10">
                        <button type="submit" class="px-3 py-1.5 rounded-lg text-white text-xs font-semibold transition" style="background-color:#A8291A">
                            <i class="fa-solid fa-plus"></i> +10 Portions
                        </button>
                    </form>
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
    <div class="bg-white rounded-xl p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-16 h-16 rounded-xl object-cover shrink-0" alt="<?= htmlspecialchars($plat->libelle) ?>">
            <div class="min-w-0 flex-1">
                <h3 class="font-bold text-sm leading-snug"><?= htmlspecialchars($plat->libelle) ?></h3>
                <?php if ($plat->categorieLibelle !== null && $plat->categorieLibelle !== ''): ?>
                <p class="text-xs text-gray-400 mb-1.5"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></p>
                <?php endif; ?>
                <?php if ($plat->estEnRupture()): ?>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-red-50 text-red-600 border border-red-100"><span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>Rupture</span>
                <?php elseif ($plat->stockFaible()): ?>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-orange-50 text-orange-600 border border-orange-100"><span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>Faible</span>
                <?php else: ?>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Optimal</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="flex items-center justify-between py-3 my-3 border-y border-gray-100">
            <div>
                <p class="text-gray-400 text-xs">Portions en stock</p>
                <p class="text-3xl font-extrabold text-gray-900"><?= $plat->quantiteStock ?></p>
            </div>
            <div class="text-right">
                <p class="text-gray-400 text-xs">Seuil d'alerte</p>
                <p class="font-semibold text-gray-700"><?= $plat->seuilAlerte ?> portions</p>
            </div>
        </div>
        <form method="post" action="/stocks/<?= $plat->id ?>/approvisionner" class="flex items-center gap-2">
            <input type="hidden" name="quantite" value="10">
            <div class="flex items-center gap-1 bg-gray-50 rounded-lg px-1 py-1" aria-hidden="true">
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 font-bold">-</span>
                <span class="w-8 h-8 flex items-center justify-center font-extrabold text-gray-900">1</span>
                <span class="w-8 h-8 flex items-center justify-center rounded-md text-gray-500 font-bold">+</span>
            </div>
            <button type="submit" class="flex-1 py-2.5 rounded-lg text-white text-xs font-bold transition hover:opacity-90" style="background-color:#A8291A">
                +10 Portions
            </button>
        </form>
    </div>
    <?php endforeach; ?>
    <?php if ($produits === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucun plat trouve.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>