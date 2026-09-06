<?php
/** @var \App\Models\Produit $produit */
/** @var \App\Models\Produit[] $suggestions */
?>
<div class="my-8">
    <a href="/produits" class="text-sm text-gray-500 hover:text-primary transition inline-flex items-center gap-2 mb-6">
        <i class="fa-solid fa-arrow-left"></i> Retour a la carte
    </a>

    <div class="grid md:grid-cols-2 gap-10 bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <div class="rounded-xl overflow-hidden h-80">
            <img src="<?= htmlspecialchars($produit->image ?? '/assets/img/produits/thiebou.jpg') ?>" alt="<?= htmlspecialchars($produit->libelle) ?>"
                 class="w-full h-full object-cover">
        </div>

        <div>
            <span class="text-primary text-xs font-bold uppercase tracking-wide"><?= htmlspecialchars((string) $produit->categorieLibelle) ?></span>
            <h1 class="text-2xl font-extrabold mt-1 mb-3"><?= htmlspecialchars($produit->libelle) ?></h1>
            <p class="text-gray-600 text-sm leading-relaxed mb-6"><?= htmlspecialchars((string) $produit->description) ?></p>

            <div class="flex items-center gap-6 mb-6 text-sm text-gray-500">
                <?php if ($produit->tempsPreparation): ?>
                <span class="flex items-center gap-2"><i class="fa-regular fa-clock text-primary"></i> <?= $produit->tempsPreparation ?> min</span>
                <?php endif; ?>
                <?php if ($produit->calories): ?>
                <span class="flex items-center gap-2"><i class="fa-solid fa-fire text-primary"></i> <?= $produit->calories ?> kcal</span>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between border-t border-gray-100 pt-6">
                <span class="text-3xl font-extrabold text-primary"><?= number_format($produit->prix, 0) ?> FCFA</span>
                <?php if ($produit->disponible()): ?>
                <button class="px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
                    Ajouter au panier
                </button>
                <?php else: ?>
                <span class="px-6 py-3 rounded-lg bg-gray-100 text-gray-400 font-semibold">Actuellement epuise</span>
                <?php endif; ?>
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
                    <img src="<?= htmlspecialchars($s->image ?? '/assets/img/produits/thiebou.jpg') ?>" alt=""
                         class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
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