<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Categories du Menu</h1>
        <p class="text-sm text-gray-500">Creez, modifiez les tarifs, stocks, temps de preparation et visuels.</p>
    </div>
    <a href="/categories/creer" class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Creer une categorie
    </a>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <form method="get" action="/categories" class="flex-1 min-w-[240px]">
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher une categorie"
               class="w-full px-4 py-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
    </form>
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

<?php if ($vue === 'tableau'): ?>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Nom</th><th class="px-4 py-3">Description</th><th class="px-4 py-3">Slug</th><th class="px-4 py-3">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($categories as $categorie): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($categorie->libelle) ?></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $categorie->description) ?></td>
                <td class="px-4 py-3 text-gray-400 italic">slug: <?= strtolower(str_replace(' ', '-', $categorie->libelle)) ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <a href="/categories/<?= $categorie->id ?>/modifier" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition"><i class="fa-regular fa-pen-to-square text-xs"></i></a>
                        <button type="button" onclick='demanderConfirmation({titre:"Supprimer cette categorie",message:"Cette operation est irreversible.",cible:<?= json_encode($categorie->libelle) ?>,actionUrl:"/categories/<?= $categorie->id ?>/delete"})' class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($categories as $categorie): ?>
    <div class="bg-white rounded-xl p-5 border-t-4 border-primary shadow-sm hover:shadow-md transition">
        <span class="w-10 h-10 rounded-lg bg-primary-light text-primary flex items-center justify-center mb-3"><i class="fa-solid fa-layer-group"></i></span>
        <h3 class="font-bold mb-1"><?= htmlspecialchars($categorie->libelle) ?></h3>
        <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars((string) $categorie->description) ?></p>
        <div class="flex items-center gap-2 pt-3 border-t border-gray-50">
            <a href="/categories/<?= $categorie->id ?>/modifier" class="flex-1 text-center py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm font-semibold transition"><i class="fa-regular fa-pen-to-square"></i></a>
            <button type="button" onclick='demanderConfirmation({titre:"Supprimer cette categorie",message:"Cette operation est irreversible.",cible:<?= json_encode($categorie->libelle) ?>,actionUrl:"/categories/<?= $categorie->id ?>/delete"})' class="flex-1 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-sm font-semibold transition text-red-600"><i class="fa-regular fa-trash-can"></i></button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>