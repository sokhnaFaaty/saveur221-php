<h1 class="text-2xl font-extrabold mb-1">Rapports & Statistiques</h1>
<p class="text-sm text-gray-500 mb-6">Suivi des ventes, repartition des encaissements et performances.</p>

<div class="grid grid-cols-1 md:grid-cols-3 gap-5">
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-2">RECETTES DU JOUR</p>
        <p class="text-2xl font-extrabold text-green-600"><?= number_format($chiffreAffaires, 0) ?> FCFA</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-2">COMMANDES EN COURS</p>
        <p class="text-2xl font-extrabold"><?= $commandesEnCours ?></p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <p class="text-xs font-bold text-gray-500 mb-2">ALERTES STOCK</p>
        <p class="text-2xl font-extrabold text-orange-600"><?= count($alertesStock) ?> plat(s)</p>
    </div>
</div>

<?php if ($alertesStock !== []): ?>
<div class="bg-white rounded-xl p-5 shadow-sm mt-6">
    <h2 class="font-bold mb-3">Plats a surveiller</h2>
    <?php foreach ($alertesStock as $p): ?>
    <div class="flex justify-between py-2 border-b border-gray-50 last:border-0 text-sm">
        <span><?= htmlspecialchars($p->libelle) ?></span>
        <span class="<?= $p->estEnRupture() ? 'text-red-600' : 'text-orange-600' ?> font-semibold"><?= $p->quantiteStock ?> restant(s)</span>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>