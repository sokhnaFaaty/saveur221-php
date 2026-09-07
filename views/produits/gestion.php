<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Gestion des Menus & Plats</h1>
        <p class="text-sm text-gray-500">Creez, modifiez les tarifs, stocks, temps de preparation et visuels.</p>
    </div>
    <a href="/produits/creer" class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Ajouter un nouveau plat
    </a>
</div>

<form method="get" action="/produits" class="mb-6">
    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher un plat, ingredient"
           class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
</form>

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
                    <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/produits/thieboudienneRouge.jpg') ?>" class="w-10 h-10 rounded-lg object-cover">
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
                        <form method="post" action="/produits/<?= $plat->id ?>/delete" onsubmit="return confirm('Supprimer ?')">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>