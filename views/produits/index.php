<div class="mt-8">
    <span class="inline-block bg-primary-light text-primary text-xs font-bold px-3 py-1 rounded-md mb-4">
        Plats frais Prepares a la Commande
    </span>

    <div class="flex items-end justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold mb-2">Notre Carte & Menus</h1>
            <p class="text-gray-500 text-sm max-w-lg">
                Parcourez nos specialites traditionnelles, nos grillades marinees et nos jus de fruits frais du Senegal.
            </p>
        </div>
    </div>

    <div class="flex flex-wrap gap-2 mb-6">
        <a href="/produits"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $categorieId === null && $terme === '' ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            Toute la carte (<?= count($produits) ?>)
        </a>
        <?php foreach ($categories as $categorie): ?>
        <a href="/produits?categorie=<?= $categorie->id ?>"
           class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $categorieId === $categorie->id ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
            <?= htmlspecialchars($categorie->libelle) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <form method="get" action="/produits" class="bg-gray-900 rounded-xl p-4 flex flex-wrap items-center gap-3 mb-6">
        <div class="flex-1 min-w-[200px] relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
            <input type="text" name="q" value="<?= htmlspecialchars($terme) ?>" placeholder="Rechercher un plat, ingredient (ex: Thie..."
                   class="w-full pl-10 pr-4 py-2.5 rounded-lg bg-gray-800 text-white text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary">
        </div>
        <div class="flex bg-gray-800 rounded-lg p-1">
            <button type="submit" name="dispo" value="tous"
                    class="px-4 py-1.5 rounded-md text-sm font-semibold <?= $dispo === 'tous' ? 'bg-white text-gray-900' : 'text-gray-300' ?>">Tous</button>
            <button type="submit" name="dispo" value="disponibles"
                    class="px-4 py-1.5 rounded-md text-sm font-semibold <?= $dispo === 'disponibles' ? 'bg-white text-gray-900' : 'text-gray-300' ?>">Disponibles</button>
        </div>
    </form>

    <p class="text-sm text-gray-500 mb-4"><strong class="text-gray-900"><?= count($produits) ?></strong> plat(s) trouve(s)</p>

    <?php if (hasRole('GERANT') || hasRole('ADMIN')): ?>
    <form method="post" action="/produits" enctype="multipart/form-data" class="bg-gray-50 border border-gray-100 rounded-xl p-5 mb-8 flex flex-wrap gap-3 items-end">
        <input type="text" name="libelle" placeholder="Nom du plat" required class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
        <input type="text" name="prix" placeholder="Prix" required class="px-3 py-2 rounded-lg border border-gray-200 text-sm w-28">
        <input type="text" name="quantite_stock" placeholder="Stock" required class="px-3 py-2 rounded-lg border border-gray-200 text-sm w-24">
        <input type="text" name="categorie_id" placeholder="ID categorie" required class="px-3 py-2 rounded-lg border border-gray-200 text-sm w-32">
        <input type="file" name="image" accept="image/png,image/jpeg,image/webp" class="text-sm">
        <button type="submit" class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition">Ajouter</button>
    </form>
    <?php endif; ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <?php foreach ($produits as $plat): ?>
        <div class="group rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">
            <div class="relative h-36 overflow-hidden">
                <img src="<?= htmlspecialchars($plat->image ?? '/assets/img/produits/thiebou.jpg') ?>" alt="<?= htmlspecialchars($plat->libelle) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-2 left-2 bg-white text-[11px] font-bold px-2 py-1 rounded">
                    <?= htmlspecialchars((string) $plat->categorieLibelle) ?>
                </span>
                <?php if ($plat->estEnRupture()): ?>
                    <span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-red-50 text-red-700">Epuise</span>
                <?php elseif ($plat->stockFaible()): ?>
                    <span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-orange-50 text-orange-700">Stock faible</span>
                <?php else: ?>
                    <span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-green-50 text-green-700">En stock</span>
                <?php endif; ?>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-sm mb-1"><?= htmlspecialchars($plat->libelle) ?></h3>
                <p class="text-xs text-gray-500 mb-3 line-clamp-2"><?= htmlspecialchars((string) $plat->description) ?></p>
                <div class="flex items-center justify-between">
                    <span class="font-extrabold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</span>
                    <div class="flex items-center gap-2">
                        <a href="/produits/<?= $plat->id ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition">
                            <i class="fa-regular fa-eye text-sm"></i>
                        </a>
                        <?php if ($plat->disponible()): ?>
                        <a href="/produits/<?= $plat->id ?>" class="px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">Commander</a>
                        <?php if (hasRole('GERANT') || hasRole('ADMIN')): ?>
                        <form method="post" action="/produits/<?= $plat->id ?>/delete" onsubmit="return confirm('Supprimer ?')">
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition">
                                <i class="fa-regular fa-trash-can text-sm"></i>
                            </button>
                        </form>
                        <?php endif; ?>
                        <?php else: ?>
                        <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 text-xs font-semibold">Epuise</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if ($produits === []): ?>
        <p class="col-span-full text-center text-gray-400 py-16">Aucun plat ne correspond a votre recherche.</p>
        <?php endif; ?>
    </div>
</div>