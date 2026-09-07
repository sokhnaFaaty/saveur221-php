<?php
/** @var \App\Models\Produit $produit */
/** @var \App\Models\Produit[] $suggestions */
?>
<div class="my-8">
    <a href="/catalogue" class="text-sm text-gray-500 hover:text-primary transition inline-flex items-center gap-2 mb-6">
        <i class="fa-solid fa-arrow-left"></i> Retour a la carte
    </a>

    <div class="grid md:grid-cols-2 gap-10 bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <div class="rounded-xl overflow-hidden h-80 relative">
            <img src="<?= htmlspecialchars($produit->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" alt="<?= htmlspecialchars($produit->libelle) ?>" class="w-full h-full object-cover">
            <?php if ($produit->disponible()): ?>
            <span class="absolute top-3 left-3 bg-green-50 text-green-700 text-xs font-bold px-2.5 py-1 rounded-full">En stock (<?= $produit->quantiteStock ?>)</span>
            <?php endif; ?>
        </div>

        <div>
            <span class="text-primary text-xs font-bold uppercase tracking-wide"><?= htmlspecialchars((string) $produit->categorieLibelle) ?></span>
            <h1 class="text-2xl font-extrabold mt-1 mb-3"><?= htmlspecialchars($produit->libelle) ?></h1>
            <p class="text-gray-600 text-sm leading-relaxed mb-4"><?= htmlspecialchars((string) $produit->description) ?></p>

            <div class="flex items-center gap-6 mb-6 text-sm text-gray-500">
                <?php if ($produit->tempsPreparation): ?>
                <span class="flex items-center gap-2"><i class="fa-regular fa-clock text-primary"></i> <?= $produit->tempsPreparation ?> min</span>
                <?php endif; ?>
                <?php if ($produit->calories): ?>
                <span class="flex items-center gap-2"><i class="fa-solid fa-fire text-primary"></i> <?= $produit->calories ?> kcal</span>
                <?php endif; ?>
                <span class="flex items-center gap-2"><i class="fa-solid fa-location-dot text-primary"></i> Origine: Senegal</span>
            </div>

            <?php if ($produit->disponible()): ?>
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-1.5">Instructions speciales pour la cuisine <span class="text-gray-400 font-normal">(optionnel)</span></label>
                <input type="text" id="instructions" placeholder="Ex: Sans piment fort, sauce a part..."
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-xs text-gray-400 mb-1">Nombre de portions</p>
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="changerPortions(-1)" class="w-8 h-8 rounded-lg border border-gray-200 hover:border-primary transition">-</button>
                        <span id="nb-portions" class="font-bold w-6 text-center">1</span>
                        <button type="button" onclick="changerPortions(1)" class="w-8 h-8 rounded-lg border border-gray-200 hover:border-primary transition">+</button>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400 mb-1">Montant Total</p>
                    <p id="montant-total" class="text-2xl font-extrabold text-primary"><?= number_format($produit->prix, 0) ?> FCFA</p>
                </div>
            </div>

            <button id="btn-ajouter" class="w-full py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
                Ajouter <span id="label-portions">1</span> portion(s) au Panier
            </button>
            <?php else: ?>
            <p class="text-3xl font-extrabold text-primary mb-4"><?= number_format($produit->prix, 0) ?> FCFA</p>
            <span class="block text-center py-3 rounded-lg bg-gray-100 text-gray-400 font-semibold">Actuellement epuise</span>
            <?php endif; ?>

            <div class="flex items-center gap-6 mt-5 pt-5 border-t border-gray-100 text-xs text-gray-500">
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-check text-green-600"></i> Livraison chaude garantie</span>
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-shield-halved text-green-600"></i> Paiement Wave & OM securise</span>
            </div>
        </div>
    </div>

    <?php if ($suggestions !== []): ?>
    <div class="mt-14">
        <h2 class="text-xl font-extrabold mb-6">Accompagnements & boissons recommandes</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <?php foreach ($suggestions as $s): ?>
            <a href="/produits/<?= $s->id ?>" class="group rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition">
                <div class="h-28 overflow-hidden">
                    <img src="<?= htmlspecialchars($s->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" alt="" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                </div>
                <div class="p-3">
                    <h3 class="text-sm font-semibold mb-1"><?= htmlspecialchars($s->libelle) ?></h3>
                    <span class="text-primary font-bold text-sm"><?= number_format($s->prix, 0) ?> FCFA</span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if ($produit->disponible()): ?>
<script>
    let portions = 1;
    const prixUnitaire = <?= $produit->prix ?>;

    function changerPortions(delta) {
        portions = Math.max(1, portions + delta);
        document.getElementById('nb-portions').textContent = portions;
        document.getElementById('label-portions').textContent = portions;
        document.getElementById('montant-total').textContent = (prixUnitaire * portions).toLocaleString() + ' FCFA';
    }

    document.getElementById('btn-ajouter').addEventListener('click', () => {
        ajouterAuPanier({
            id: <?= $produit->id ?>,
            nom: <?= json_encode($produit->libelle) ?>,
            prix: prixUnitaire,
            image: <?= json_encode($produit->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>,
        }, portions);
    });
</script>
<?php endif; ?>