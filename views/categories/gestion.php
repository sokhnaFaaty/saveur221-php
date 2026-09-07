<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Categories du Menu</h1>
        <p class="text-sm text-gray-500">Creez, modifiez les tarifs, stocks, temps de preparation et visuels des specialites.</p>
    </div>
    <a href="/categories/creer" class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Creer une categorie
    </a>
</div>

<form method="get" action="/categories" class="mb-6">
    <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher une categorie"
           class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
</form>

<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($categories as $categorie): ?>
    <div class="bg-white rounded-xl p-5 border-t-4 border-primary shadow-sm hover:shadow-md transition">
        <div class="flex items-start justify-between mb-3">
            <span class="w-10 h-10 rounded-lg bg-primary-light text-primary flex items-center justify-center"><i class="fa-solid fa-layer-group"></i></span>
        </div>
        <h3 class="font-bold mb-1"><?= htmlspecialchars($categorie->libelle) ?></h3>
        <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars((string) $categorie->description) ?></p>
        <p class="text-xs text-gray-400 italic mb-4">slug: <?= strtolower(str_replace(' ', '-', $categorie->libelle)) ?></p>
        <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
            <a href="/categories/<?= $categorie->id ?>/modifier" class="flex-1 text-center py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm font-semibold transition">
                <i class="fa-regular fa-pen-to-square"></i>
            </a>
            <form method="post" action="/categories/<?= $categorie->id ?>/delete" onsubmit="return confirm('Supprimer cette categorie ?')" class="flex-1">
                <button type="submit" class="w-full py-2 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold transition">
                    <i class="fa-regular fa-trash-can"></i>
                </button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
</div>