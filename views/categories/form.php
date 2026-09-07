<?php /** @var \App\Models\Categorie|null $categorie */ ?>
<a href="/categories" class="text-sm text-gray-500 hover:text-primary transition mb-4 inline-flex items-center gap-2">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>
<div class="bg-white rounded-xl p-6 shadow-sm max-w-lg">
    <h1 class="text-xl font-extrabold mb-5"><?= $categorie ? 'Modifier la categorie' : 'Nouvelle categorie' ?></h1>
    <form method="post" action="<?= $categorie ? '/categories/' . $categorie->id . '/update' : '/categories' ?>" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Nom de la categorie</label>
            <input type="text" name="libelle" value="<?= htmlspecialchars($categorie->libelle ?? '') ?>"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Description courte</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary"><?= htmlspecialchars($categorie->description ?? '') ?></textarea>
        </div>
        <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition">Enregistrer</button>
    </form>
</div>