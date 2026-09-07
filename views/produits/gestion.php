<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Gestion des Menus & Plats</h1>
        <p class="text-sm text-gray-500">Créez, modifiez les tarifs, stocks, temps de préparation et visuels.</p>
    </div>
    <button type="button" onclick="ouvrirDrawerProduit()" class="px-4 py-2.5 rounded-lg text-white text-sm font-semibold transition flex items-center gap-2" style="background-color:#B83518">
        <i class="fa-solid fa-plus"></i> Ajouter un nouveau plat
    </button>
</div>

<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <form method="get" action="/produits" class="flex-1 min-w-[240px] flex flex-wrap items-center gap-3">
        <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher un plat, ingrédient"
               class="flex-1 min-w-[180px] px-4 py-3 rounded-lg border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        <div class="relative">
            <select name="categorie" onchange="this.form.submit()"
                    class="appearance-none w-full min-w-[220px] px-4 py-3 pr-9 rounded-lg border border-gray-200 text-sm bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-primary cursor-pointer">
                <option value="">Toutes les catégories</option>
                <?php foreach ($categoriesProduits as $cat): ?>
                <option value="<?= $cat->id ?>" <?= ($categorieId ?? null) === $cat->id ? 'selected' : '' ?>><?= htmlspecialchars($cat->libelle) ?></option>
                <?php endforeach; ?>
            </select>
            <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        <button type="submit" class="px-4 py-3 rounded-lg text-white text-sm font-semibold transition flex items-center gap-2" style="background-color:#B83518">
            <i class="fa-solid fa-magnifying-glass"></i> Filtrer
        </button>
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
            <tr>
                <th class="px-4 py-3">Plat</th><th class="px-4 py-3">Categorie</th><th class="px-4 py-3">Prix</th>
                <th class="px-4 py-3">Stock</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach ($produits as $plat): ?>
            <tr class="hover:bg-gray-50"
                data-produit='<?= htmlspecialchars(json_encode([
                    'id' => $plat->id,
                    'libelle' => $plat->libelle,
                    'description' => (string) $plat->description,
                    'prix' => $plat->prix,
                    'quantite_stock' => $plat->quantiteStock,
                    'categorie_id' => $plat->categorieId,
                    'seuil_alerte' => $plat->seuilAlerte,
                    'temps_preparation' => $plat->tempsPreparation,
                    'calories' => $plat->calories,
                    'image' => (string) $plat->image,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>'>
                <td class="px-4 py-3 flex items-center gap-3">
                    <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-10 h-10 rounded-lg object-cover">
                    <div>
                        <p class="font-semibold"><?= htmlspecialchars($plat->libelle) ?></p>
                        <?php if ($plat->tempsPreparation): ?><p class="text-xs text-gray-400">~<?= $plat->tempsPreparation ?> min prep</p><?php endif; ?>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></td>
                <td class="px-4 py-3 font-bold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</td>
                <td class="px-4 py-3"><?= $plat->quantiteStock ?> portion(s)</td>
                <td class="px-4 py-3">
                    <?php if ($plat->estEnRupture()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-50 text-red-700">Rupture</span>
                    <?php elseif ($plat->stockFaible()): ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-orange-50 text-orange-700">Stock faible</span>
                    <?php else: ?><span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-50 text-green-700">En stock</span><?php endif; ?>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="ouvrirDrawerProduit(this.closest('tr'))" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition"><i class="fa-regular fa-pen-to-square text-xs"></i></button>
                        <button type="button" onclick='demanderConfirmation({titre:"Supprimer ce plat",message:"Cette opération est irréversible.",cible:<?= json_encode($plat->libelle) ?>,actionUrl:"/produits/<?= $plat->id ?>/delete"})' class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if ($produits === []): ?><tr><td colspan="6" class="text-center text-gray-400 py-10">Aucun plat trouve.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 items-stretch">
    <?php foreach ($produits as $plat): ?>
    <div class="bg-white rounded-xl border-t-4 border-primary shadow-sm hover:shadow-md transition overflow-hidden flex flex-col" data-produit='<?= htmlspecialchars(json_encode([
        'id' => $plat->id,
        'libelle' => $plat->libelle,
        'description' => (string) $plat->description,
        'prix' => $plat->prix,
        'quantite_stock' => $plat->quantiteStock,
        'categorie_id' => $plat->categorieId,
        'seuil_alerte' => $plat->seuilAlerte,
        'temps_preparation' => $plat->tempsPreparation,
        'calories' => $plat->calories,
        'image' => (string) $plat->image,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), ENT_QUOTES) ?>'>
        <div class="relative h-36">
            <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-full h-full object-cover" alt="<?= htmlspecialchars($plat->libelle) ?>">
            <?php if ($plat->estEnRupture()): ?><span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-red-50 text-red-700">Rupture</span>
            <?php elseif ($plat->stockFaible()): ?><span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-orange-50 text-orange-700">Stock faible</span>
            <?php else: ?><span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-green-50 text-green-700">En stock</span><?php endif; ?>
        </div>
        <div class="p-4 flex flex-col flex-1">
            <h3 class="font-bold mb-1"><?= htmlspecialchars($plat->libelle) ?></h3>
            <p class="text-sm text-gray-500 mb-3"><?= htmlspecialchars(mb_strimwidth((string) ($plat->description ?: '-'), 0, 120, '...')) ?></p>
            <div class="flex items-center justify-between mb-3">
                <span class="font-extrabold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</span>
                <span class="text-xs text-gray-400"><?= $plat->quantiteStock ?> portion(s)</span>
            </div>
            <div class="mt-auto flex items-center gap-2 pt-3 border-t border-gray-50">
                <button type="button" onclick="ouvrirDrawerProduit(this.closest('div[data-produit]'))" class="flex-1 text-center py-2 rounded-lg bg-gray-50 hover:bg-gray-100 text-sm font-semibold transition"><i class="fa-regular fa-pen-to-square"></i></button>
                <button type="button" onclick='demanderConfirmation({titre:"Supprimer ce plat",message:"Cette opération est irréversible.",cible:<?= json_encode($plat->libelle) ?>,actionUrl:"/produits/<?= $plat->id ?>/delete"})' class="flex-1 py-2 rounded-lg bg-red-50 hover:bg-red-100 text-sm font-semibold transition text-red-600"><i class="fa-regular fa-trash-can"></i></button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if ($produits === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucun plat trouve.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>

<div id="drawer-produit" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="fermerDrawerProduit()"></div>
    <aside class="absolute top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 id="titre-drawer-produit" class="text-lg font-extrabold">Ajouter un plat</h2>
            <button type="button" onclick="fermerDrawerProduit()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-500"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form method="post" id="form-produit" action="/produits" enctype="multipart/form-data" class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
            <input type="hidden" id="produit_id" name="id" value="">
            <div>
                <label class="block text-sm font-semibold mb-1">Nom du plat</label>
                <input type="text" id="produit_libelle" name="libelle" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Categorie</label>
                    <select id="produit_categorie_id" name="categorie_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm bg-white">
                        <?php foreach ($categoriesProduits ?? [] as $c): ?>
                        <option value="<?= $c->id ?>"><?= htmlspecialchars($c->libelle) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Prix (FCFA)</label>
                    <input type="text" id="produit_prix" name="prix" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Stock initial</label>
                    <input type="text" id="produit_quantite_stock" name="quantite_stock" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Seuil alerte</label>
                    <input type="text" id="produit_seuil_alerte" name="seuil_alerte" value="5" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Temps prep (min)</label>
                    <input type="text" id="produit_temps_preparation" name="temps_preparation" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Calories</label>
                    <input type="text" id="produit_calories" name="calories" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Description detaillee</label>
                <textarea id="produit_description" name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm"></textarea>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-2">Image <span class="text-xs font-normal text-gray-400">(choisissez une option)</span></label>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <input type="radio" name="image_option" value="file" id="opt_file" checked class="accent-red-600">
                        <label for="opt_file" class="text-sm font-semibold">Option A : Téléverser un fichier</label>
                    </div>
                    <div class="pl-6" id="bloc-file">
                        <input type="file" name="image_file" accept="image/png,image/jpeg,image/webp" class="text-sm">
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <input type="radio" name="image_option" value="url" id="opt_url" class="accent-red-600">
                        <label for="opt_url" class="text-sm font-semibold">Option B : Lien internet</label>
                    </div>
                    <div class="pl-6 hidden" id="bloc-url">
                        <input type="text" name="image_url" id="produit_image_url" placeholder="https://example.com/image.jpg"
                               class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full py-2.5 rounded-lg text-white font-semibold text-sm transition" style="background-color:#B83518">Enregistrer</button>
        </form>
    </aside>
</div>

<script>
    const drawerProduit = document.getElementById('drawer-produit');
    const asideProduit = drawerProduit.querySelector('aside');

    function ouvrirDrawerProduit(source) {
        const form = document.getElementById('form-produit');
        const titre = document.getElementById('titre-drawer-produit');
        form.reset();
        form.action = '/produits';
        document.getElementById('produit_id').value = '';
        titre.textContent = 'Ajouter un plat';
        document.getElementById('opt_file').checked = true;
        document.getElementById('bloc-file').classList.remove('hidden');
        document.getElementById('bloc-url').classList.add('hidden');

        if (source) {
            const data = JSON.parse(source.dataset.produit);
            document.getElementById('produit_id').value = data.id;
            document.getElementById('produit_libelle').value = data.libelle || '';
            document.getElementById('produit_description').value = data.description || '';
            document.getElementById('produit_prix').value = data.prix ?? '';
            document.getElementById('produit_quantite_stock').value = data.quantite_stock ?? '';
            document.getElementById('produit_categorie_id').value = data.categorie_id ?? '';
            document.getElementById('produit_seuil_alerte').value = data.seuil_alerte ?? '5';
            document.getElementById('produit_temps_preparation').value = data.temps_preparation ?? '';
            document.getElementById('produit_calories').value = data.calories ?? '';
            document.getElementById('produit_image_url').value = data.image || '';
            form.action = '/produits/' + data.id + '/update';
            titre.textContent = 'Modifier le plat';
        }

        drawerProduit.classList.remove('hidden');
        requestAnimationFrame(() => asideProduit.classList.remove('translate-x-full'));
        drawerProduit.querySelector('input[name="libelle"]').focus();
    }

    function fermerDrawerProduit() {
        asideProduit.classList.add('translate-x-full');
        setTimeout(() => drawerProduit.classList.add('hidden'), 300);
    }

    document.querySelectorAll('#form-produit input[name="image_option"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const utiliserFichier = document.getElementById('opt_file').checked;
            document.getElementById('bloc-file').classList.toggle('hidden', !utiliserFichier);
            document.getElementById('bloc-url').classList.toggle('hidden', utiliserFichier);
            if (utiliserFichier) {
                document.querySelector('#form-produit input[name="image_url"]').value = '';
            } else {
                document.querySelector('#form-produit input[name="image_file"]').value = '';
            }
        });
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !drawerProduit.classList.contains('hidden')) fermerDrawerProduit();
    });
</script>
