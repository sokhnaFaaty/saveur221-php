<?php /** @var \App\Models\Categorie|null $categorie */ ?>
<a href="/categories" class="text-sm text-gray-500 hover:text-primary transition mb-4 inline-flex items-center gap-2">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
<div class="bg-white rounded-xl p-6 shadow-sm max-w-lg">
    <h1 class="text-xl font-extrabold mb-5"><?= $categorie ? 'Modifier la categorie' : 'Nouvelle categorie' ?></h1>
    <form method="post" action="<?= $categorie ? '/categories/' . $categorie->id . '/update' : '/categories' ?>" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Nom de la categorie</label>
            <input type="text" name="libelle" value="<?= htmlspecialchars(ancienneValeur('libelle', (string) ($categorie->libelle ?? ''))) ?>"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary <?= erreurChamp('libelle') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('libelle')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Description courte</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary <?= erreurChamp('description') !== '' ? 'border-red-500' : '' ?>"><?= htmlspecialchars(ancienneValeur('description', (string) ($categorie->description ?? ''))) ?></textarea>
            <?php if ($erreur = erreurChamp('description')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Image (optionnel)</label>
            <input type="file" name="image" accept="image/jpeg,image/png,image/webp"
                   class="w-full text-sm text-gray-500 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0
                          file:bg-primary file:text-white file:text-sm file:font-semibold
                          hover:file:bg-primary-dark file:cursor-pointer cursor-pointer transition">
            <?php if (!empty($categorie->image)): ?>
                <div class="mt-3 flex items-center gap-3">
                    <img src="<?= htmlspecialchars($categorie->image) ?>" alt="<?= htmlspecialchars($categorie->libelle) ?>"
                         class="w-20 h-16 rounded-lg object-cover border border-gray-200 shadow-sm">
                    <p class="text-xs text-gray-400">Image actuelle — gardée si aucun nouveau fichier choisi.</p>
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition">Enregistrer</button>
    </form>
</div>
<?php effacerErreursFormulaire(); ?>