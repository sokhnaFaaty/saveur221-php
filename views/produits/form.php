<?php /** @var \App\Models\Produit|null $produit */ /** @var \App\Models\Categorie[] $categories */ ?>
<a href="/produits" class="text-sm text-gray-500 hover:text-primary transition mb-4 inline-flex items-center gap-2"><i class="fa-solid fa-arrow-left"></i> Retour</a>
<div class="bg-white rounded-xl p-6 shadow-sm max-w-2xl">
    <h1 class="text-xl font-extrabold mb-5"><?= $produit ? 'Modifier un plat' : 'Ajouter un plat' ?></h1>
    <form method="post" action="<?= $produit ? '/produits/' . $produit->id . '/update' : '/produits' ?>" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Nom du plat</label>
            <input type="text" name="libelle" value="<?= htmlspecialchars($produit->libelle ?? '') ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Categorie</label>
                <select name="categorie_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm bg-white">
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= ($produit && $produit->categorieId === $c->id) ? 'selected' : '' ?>><?= htmlspecialchars($c->libelle) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Prix (FCFA)</label>
                <input type="text" name="prix" value="<?= htmlspecialchars((string) ($produit->prix ?? '')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div><label class="block text-sm font-semibold mb-1">Stock initial</label>
                <input type="text" name="quantite_stock" value="<?= htmlspecialchars((string) ($produit->quantiteStock ?? '')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm"></div>
            <div><label class="block text-sm font-semibold mb-1">Seuil alerte</label>
                <input type="text" name="seuil_alerte" value="<?= htmlspecialchars((string) ($produit->seuilAlerte ?? '5')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm"></div>
            <div><label class="block text-sm font-semibold mb-1">Temps prep (min)</label>
                <input type="text" name="temps_preparation" value="<?= htmlspecialchars((string) ($produit->tempsPreparation ?? '')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm"></div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Description detaillee</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm"><?= htmlspecialchars($produit->description ?? '') ?></textarea>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Image</label>
            <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="text-sm">
            <?php if ($produit && $produit->image): ?><p class="text-xs text-gray-400 mt-1">Laisser vide pour conserver l'image actuelle.</p><?php endif; ?>
        </div>
        <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition">Enregistrer</button>
    </form>
</div>