<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Catégories du Menu</h1>
        <p class="text-sm text-gray-500">Gérez les catégories de plats et spécialités.</p>
    </div>
    <button type="button" onclick="ouvrirDrawerCategorie()" class="px-4 py-2.5 rounded-lg text-white text-sm font-semibold transition flex items-center gap-2" style="background-color:#B83518">
        <i class="fa-solid fa-plus"></i> Créer une catégorie
    </button>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <form method="get" action="/categories" class="flex-1 min-w-[240px]">
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher une catégorie"
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
            <tr class="hover:bg-gray-50" data-categorie='<?= htmlspecialchars(json_encode([
                'id' => $categorie->id,
                'libelle' => $categorie->libelle,
                'description' => (string) $categorie->description,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>'>
                <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($categorie->libelle) ?></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $categorie->description) ?></td>
                <td class="px-4 py-3 text-gray-400 italic">slug: <?= strtolower(str_replace(' ', '-', $categorie->libelle)) ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="ouvrirDrawerCategorie(this.closest('tr'))" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition"><i class="fa-regular fa-pen-to-square text-xs"></i></button>
                        <button type="button" onclick='demanderConfirmation({titre:"Supprimer cette catégorie",message:"Cette opération est irréversible.",cible:<?= json_encode($categorie->libelle) ?>,actionUrl:"/categories/<?= $categorie->id ?>/delete"})' class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($categories === []): ?><tr><td colspan="4" class="text-center text-gray-400 py-10">Aucune catégorie trouvée.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 items-stretch">
    <?php foreach ($categories as $categorie): ?>
    <div class="bg-white rounded-xl p-5 border-t-4 border-primary shadow-sm hover:shadow-md transition flex flex-col" data-categorie='<?= htmlspecialchars(json_encode([
        'id' => $categorie->id,
        'libelle' => $categorie->libelle,
        'description' => (string) $categorie->description,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>'>
        <span class="w-10 h-10 rounded-lg bg-primary-light text-primary flex items-center justify-center mb-3"><i class="fa-solid fa-layer-group"></i></span>
        <h3 class="font-bold mb-1"><?= htmlspecialchars($categorie->libelle) ?></h3>
        <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars(mb_strimwidth((string) $categorie->description, 0, 100, '...')) ?></p>
        <div class="mt-auto flex items-center gap-2 pt-3 border-t border-gray-50">
            <button type="button" onclick="ouvrirDrawerCategorie(this.closest('div[data-categorie]'))" class="flex-1 text-center py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm font-semibold transition"><i class="fa-regular fa-pen-to-square"></i></button>
            <button type="button" onclick='demanderConfirmation({titre:"Supprimer cette catégorie",message:"Cette opération est irréversible.",cible:<?= json_encode($categorie->libelle) ?>,actionUrl:"/categories/<?= $categorie->id ?>/delete"})' class="flex-1 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-sm font-semibold transition text-red-600"><i class="fa-regular fa-trash-can"></i></button>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if ($categories === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucune catégorie trouvée.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>

<div id="drawer-categorie" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="fermerDrawerCategorie()"></div>
    <aside class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 id="titre-drawer-categorie" class="text-lg font-extrabold">Créer une catégorie</h2>
            <button type="button" onclick="fermerDrawerCategorie()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="post" id="form-categorie" action="/categories" class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
            <input type="hidden" id="categorie_id" name="id" value="">
            <div>
                <label class="block text-sm font-semibold mb-1">Libellé</label>
                <input type="text" id="categorie_libelle" name="libelle" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Description</label>
                <textarea id="categorie_description" name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm"></textarea>
            </div>
            <button type="submit" class="w-full py-2.5 rounded-lg text-white font-semibold text-sm transition" style="background-color:#B83518">Enregistrer</button>
        </form>
    </aside>
</div>

<script>
    const drawerCategorie = document.getElementById('drawer-categorie');
    const asideCategorie = drawerCategorie.querySelector('aside');

    function ouvrirDrawerCategorie(source) {
        const form = document.getElementById('form-categorie');
        const titre = document.getElementById('titre-drawer-categorie');
        form.reset();
        form.action = '/categories';
        document.getElementById('categorie_id').value = '';
        titre.textContent = 'Créer une catégorie';

        if (source) {
            const data = JSON.parse(source.dataset.categorie);
            document.getElementById('categorie_id').value = data.id;
            document.getElementById('categorie_libelle').value = data.libelle || '';
            document.getElementById('categorie_description').value = data.description || '';
            form.action = '/categories/' + data.id + '/update';
            titre.textContent = 'Modifier la catégorie';
        }

        drawerCategorie.classList.remove('hidden');
        requestAnimationFrame(() => asideCategorie.classList.remove('translate-x-full'));
        drawerCategorie.querySelector('input[name="libelle"]').focus();
    }

    function fermerDrawerCategorie() {
        asideCategorie.classList.add('translate-x-full');
        setTimeout(() => drawerCategorie.classList.add('hidden'), 300);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !drawerCategorie.classList.contains('hidden')) fermerDrawerCategorie();
    });
</script>
