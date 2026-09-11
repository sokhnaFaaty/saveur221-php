<?php $dispo = $dispo ?? 'tous'; ?>
<div class="mt-8">
    <span class="inline-block bg-primary-light text-primary text-xs font-bold px-3 py-1 rounded-md mb-4">
        Plats frais Préparés à la Commande
    </span>

    <div class="flex items-end justify-between flex-wrap gap-4 mb-6">
        <div>
            <h1 class="text-3xl font-extrabold mb-2">Notre Carte & Menus</h1>
            <p class="text-gray-500 text-sm max-w-lg">
                Parcourez nos spécialités traditionnelles, nos grillades marinées et nos jus de fruits frais du Sénégal.
            </p>
        </div>
    </div>

    <!-- Barre de recherche + filtres par categorie (charte #A8291A) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 space-y-4">
        <!-- Ligne 1 : barre de recherche + toggle dispo -->
        <form method="get" action="/catalogue" class="flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($terme) ?>" placeholder="Rechercher un plat, ingrédient (ex: Thie...)"
                       class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#A8291A] focus:border-[#A8291A]">
            </div>
            <div class="flex bg-gray-100 rounded-lg p-1">
                <button type="submit" name="dispo" value="tous"
                        class="px-4 py-1.5 rounded-md text-sm font-semibold transition <?= $dispo === 'tous' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' ?>">Tous</button>
                <button type="submit" name="dispo" value="disponibles"
                        class="px-4 py-1.5 rounded-md text-sm font-semibold transition <?= $dispo === 'disponibles' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500 hover:text-gray-900' ?>">Disponibles</button>
            </div>
        </form>

        <!-- Ligne 2 : boutons de filtrage par categorie (boucle sur $categories) -->
        <div class="flex flex-wrap items-center gap-2 pt-4 border-t border-gray-100">
            <span class="text-xs font-bold uppercase tracking-wide text-gray-400 mr-1">Filtrer :</span>

            <!-- Tous : actif quand aucune categorie ni recherche -->
            <a href="/catalogue"
               class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $categorieId === null && $terme === '' ? 'bg-[#A8291A] text-white shadow-sm' : 'bg-white border border-gray-300 text-gray-700 hover:border-[#A8291A] hover:text-[#A8291A]' ?>">
                Tous (<?= count($produits) ?>)
            </a>

            <!-- Une maille par categorie : class active = fond #A8291A -->
            <?php foreach ($categories as $categorie): ?>
            <a href="/catalogue?categorie=<?= $categorie->id ?>"
               class="px-4 py-2 rounded-lg text-sm font-semibold transition <?= $categorieId === $categorie->id ? 'bg-[#A8291A] text-white shadow-sm' : 'bg-white border border-gray-300 text-gray-700 hover:border-[#A8291A] hover:text-[#A8291A]' ?>">
                <?= htmlspecialchars($categorie->libelle) ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <p class="text-sm text-gray-500 mb-4"><strong class="text-gray-900"><?= count($produits) ?></strong> plat(s) trouvé(s)</p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
        <?php foreach ($produits as $plat): ?>
        <div class="group rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">
            <div class="relative h-36 overflow-hidden">
                <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" alt="<?= htmlspecialchars($plat->libelle) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-2 left-2 bg-white text-[11px] font-bold px-2 py-1 rounded">
                    <?= htmlspecialchars((string) $plat->categorieLibelle) ?>
                </span>
                <?php if ($plat->estEnRupture()): ?>
                    <span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded bg-red-50 text-red-700">Épuisé</span>
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
                        <?php if ($plat->disponible() && (!isConnected() || hasRole('CLIENT'))): ?>
                        <button
                            onclick="<?= htmlspecialchars('ajouterAuPanier({ id: ' . $plat->id . ', nom: ' . json_encode($plat->libelle) . ', prix: ' . $plat->prix . ', image: ' . json_encode($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') . ' })', ENT_QUOTES, 'UTF-8') ?>"
                            class="px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                            Commander
                        </button>
                        <?php elseif ($plat->disponible()): ?>
                        <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 text-xs font-semibold">Consulter le ticket</span>
                        <?php else: ?>
                        <span class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-400 text-xs font-semibold">Épuisé</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <?php if ($produits === []): ?>
        <p class="col-span-full text-center text-gray-400 py-16">Aucun plat ne correspond à votre recherche.</p>
        <?php endif; ?>
    </div>
</div>