<h1 class="text-2xl font-extrabold mb-1">Gestion & Reapprovisionnement des Stocks</h1>
<p class="text-sm text-gray-500 mb-6">Suivez les seuils d'alerte, reapprovisionnez les portions cuisinees.</p>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($produits as $plat): ?>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <div class="flex items-start justify-between mb-3">
            <div>
                <h3 class="font-bold text-sm"><?= htmlspecialchars($plat->libelle) ?></h3>
                <p class="text-xs text-gray-400"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></p>
            </div>
            <?php if ($plat->estEnRupture()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-50 text-red-700">Rupture</span>
            <?php elseif ($plat->stockFaible()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-orange-50 text-orange-700">Faible</span>
            <?php else: ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-50 text-green-700">Optimal</span><?php endif; ?>
        </div>
        <div class="flex justify-between text-sm bg-gray-50 rounded-lg p-3 mb-4">
            <div><p class="text-gray-400 text-xs">Portions en stock</p><p class="font-extrabold text-lg"><?= $plat->quantiteStock ?></p></div>
            <div class="text-right"><p class="text-gray-400 text-xs">Seuil d'alerte</p><p class="font-semibold"><?= $plat->seuilAlerte ?> portions</p></div>
        </div>
        <form method="post" action="/stocks/<?= $plat->id ?>/approvisionner" class="flex items-center gap-2">
            <input type="text" name="quantite" value="10" class="w-16 px-2 py-1.5 rounded-lg border border-gray-200 text-sm text-center">
            <button type="submit" class="flex-1 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                <i class="fa-solid fa-plus"></i> Portions
            </button>
        </form>
    </div>
    <?php endforeach; ?>
</div>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>