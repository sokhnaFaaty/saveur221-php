<?php
/** @var \App\Models\Categorie[] $categories */
/** @var \App\Models\Produit[] $plats */

$images = [
    'hero'          => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788727588/saveur221/images/cotelettes-grillees.jpg',
    'grillade'      => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725699/saveur221/images/grillade-dibiterie.jpg',
    'thieboudienne' => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725692/saveur221/images/thieboudienne-rouge.jpg',
    'brochettes'    => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725696/saveur221/images/brochette-dibi.jpg',
];
?>

<section class="relative overflow-hidden ml-[calc(50%_-_50vw)] mr-[calc(50%_-_50vw)]">
    <div class="relative min-h-[520px] md:min-h-[560px] bg-cover bg-center" style="background-image:url('<?= $images['hero'] ?>')">
        <div class="absolute inset-0 bg-black/55"></div>
        <div class="relative grid md:grid-cols-[1.4fr,1fr] gap-8 md:gap-10 items-center px-6 md:px-16 py-16 md:py-10">
            <div class="text-white">
                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">
                    La Haute Gastronomie <span class="text-primary">Sénégalaise</span> chez Vous
                </h1>
                <p class="text-gray-200 mb-6 max-w-md">
                    Dégustez nos recettes emblématiques mijotées dans le respect des traditions :
                    Thiéboudiène Penda Mbaye au Thiof frais, Yassa au Poulet braisé, Dibi d'agneau au feu de bois.
                </p>
                <div class="flex flex-wrap gap-3 mb-6">
                    <a href="/catalogue" class="px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
                        Commander maintenant
                    </a>
                    <a href="#incontournables" class="px-6 py-3 rounded-lg border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                        Découvrez le Thiéboudiène du Chef
                    </a>
                </div>
                <div class="flex flex-wrap gap-5 text-sm text-gray-300">
                    <span>100% Ingrédients Locaux Frais</span>
                    <span>Paiement Wave et OM 0% frais</span>
                    <span>Emballages thermo-scellés</span>
                </div>
            </div>

            <?php if (!empty($plats[0])): $vedette = $plats[0]; ?>
            <div class="z-10 rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10 bg-[#111827]/90 backdrop-blur-md">
                <div class="relative h-48">
                    <img src="<?= htmlspecialchars((string) ($vedette->image ?: $images['thieboudienne'])) ?>" alt="<?= htmlspecialchars($vedette->libelle) ?>" class="w-full h-full object-cover">
                    <span class="absolute top-3 right-3 flex items-center gap-1.5 bg-white/95 text-amber-500 text-sm font-bold px-2.5 py-1 rounded-full shadow-lg">
                        <i class="fa-solid fa-star"></i> 4.9/5
                    </span>
                </div>
                <div class="p-5 text-white">
                    <span class="inline-block bg-primary text-white text-xs px-3 py-1 rounded-md mb-3">Plat Signature</span>
                    <h3 class="font-bold text-lg mb-1"><?= htmlspecialchars($vedette->libelle) ?></h3>
                    <p class="text-sm text-gray-300 mb-4"><?= htmlspecialchars((string) $vedette->description) ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-primary font-extrabold text-xl"><?= number_format($vedette->prix, 0) ?> FCFA</span>
                        <div class="flex items-center gap-2">
                            <a href="/produits/<?= $vedette->id ?>" class="w-9 h-9 flex items-center justify-center rounded-lg border border-white/30 text-white hover:bg-white/15 transition" title="Voir le détail">
                                <i class="fa-regular fa-eye text-sm"></i>
                            </a>
                            <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition" onclick="<?= htmlspecialchars('ajouterAuPanier({ id: ' . $vedette->id . ', nom: ' . json_encode($vedette->libelle) . ', prix: ' . $vedette->prix . ', image: ' . json_encode($vedette->image ?: $images['thieboudienne']) . ' })', ENT_QUOTES, 'UTF-8') ?>">Ajouter</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 my-10 md:my-12">
    <?php foreach ([
        ['fa-fire', 'Braise Artisanale', 'Cuisson au feu de bois', 'bg-red-50', 'text-red-700', 'text-red-700'],
        ['fa-clock', '30 à 45 Minutes', 'Livraison rapide Dakar', 'bg-amber-50', 'text-amber-500', ''],
        ['fa-shield-halved', 'Wave & OM 0% Frais', 'Paiement 100% Sécurisé', 'bg-green-50', 'text-green-600', ''],
        ['fa-box', 'Chaud & Hermétique', 'Conditionnement isotherme', 'bg-gray-50', 'text-gray-700', ''],
    ] as [$icone, $titre, $sous, $fond, $couleurIcone, $couleurTitre]): ?>
    <div class="flex gap-3 bg-white rounded-xl p-3 sm:p-4 shadow-md min-w-0">
        <div class="w-10 h-10 sm:w-11 sm:h-11 rounded-lg <?= $fond ?> flex items-center justify-center <?= $couleurIcone ?> shrink-0">
            <i class="fa-solid <?= $icone ?>"></i>
        </div>
        <div class="min-w-0">
            <strong class="block text-sm <?= $couleurTitre ?> leading-snug break-words"><?= $titre ?></strong>
            <span class="text-xs text-gray-500 leading-snug break-words"><?= $sous ?></span>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<section id="categories" class="mb-14 scroll-mt-24">
    <p class="text-primary font-bold text-xs uppercase tracking-wide mb-1">Explorez notre carte</p>
    <h2 class="text-2xl font-extrabold mb-6">Catégories de Plats & Spécialités</h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-5">
<?php foreach ($categories as $categorie): ?>
        <?php
        // Image de la categorie : 1) colonne image 2) image du 1er produit dispo
        // de la categorie (ex: Pastels au Thon) 3) fallback par mot-cle du libelle.
        $imageCategorie = (string) ($categorie->image ?: ($imagesParCategorie[$categorie->id] ?? ''));
        if ($imageCategorie === '') {
            $libelle = mb_strtolower($categorie->libelle);
            $imageCategorie = $images['grillade'];
            if (str_contains($libelle, 'thieboudien') || str_contains($libelle, 'plat')) {
                $imageCategorie = '/assets/img/maquettes/ThieboudienneRouge.jpg';
            } elseif (str_contains($libelle, 'boisson') || str_contains($libelle, 'jus') || str_contains($libelle, 'bissap')) {
                $imageCategorie = 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788726510/saveur221/images/bissap-boisson.jpg';
            } elseif (str_contains($libelle, 'dessert') || str_contains($libelle, 'douceur') || str_contains($libelle, 'thiakry')) {
                $imageCategorie = '/assets/img/maquettes/thiakry.jpg';
            } elseif (str_contains($libelle, 'grill') || str_contains($libelle, 'dibiterie') || str_contains($libelle, 'dibi')) {
                $imageCategorie = '/assets/img/maquettes/grillade.jpg';
            } elseif (str_contains($libelle, 'pastel') || str_contains($libelle, 'entrée') || str_contains($libelle, 'entree')) {
                $imageCategorie = '/assets/img/maquettes/boudieune.jpg';
            }
        }
        ?>
        <a href="/catalogue?categorie=<?= $categorie->id ?>" class="group rounded-xl overflow-hidden border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300">
            <div class="h-32 overflow-hidden">
                <img src="<?= htmlspecialchars($imageCategorie) ?>" alt="<?= htmlspecialchars($categorie->libelle) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="p-3">
                <h3 class="font-semibold text-sm"><?= htmlspecialchars($categorie->libelle) ?></h3>
                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars((string) $categorie->description) ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>

    <?php
    // Pagination des categories (page_categories pour ne pas interferer avec d'autres pages)
    $page = $pageCategories ?? 1;
    $totalPages = $totalPagesCategories ?? 1;
    $pageVar = 'page_categories';
    $anchor = 'categories';
    include VIEW_PATH . '/partials/pagination.php';
    ?>
</section>

<section id="incontournables" class="mb-14">
    <p class="text-primary font-bold text-xs uppercase tracking-wide mb-1">Les incontournables</p>
    <h2 class="text-2xl font-extrabold mb-6">Plats Coup de Cœur de Dakar</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach (array_slice($plats, 0, 5) as $plat): ?>
        <div class="group rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">
            <div class="relative h-36 overflow-hidden">
                <img src="<?= htmlspecialchars($plat->image ?: $images['thieboudienne']) ?>" alt="<?= htmlspecialchars($plat->libelle) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-2 left-2 bg-white text-[11px] font-bold px-2 py-1 rounded">
                    <?= htmlspecialchars((string) $plat->categorieLibelle) ?>
                </span>
                <span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded
                    <?= $plat->disponible() ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
                    <?= $plat->disponible() ? 'En stock' : 'Épuisé' ?>
                </span>
                <span class="absolute bottom-2 left-2 bg-black/60 text-white text-[11px] px-2 py-0.5 rounded flex items-center gap-1">
    <i class="fa-regular fa-clock"></i> <?= $plat->tempsPreparation ?? '?' ?> mn
</span>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-sm mb-1"><?= htmlspecialchars($plat->libelle) ?></h3>
                <p class="text-xs text-gray-500 mb-3 line-clamp-2"><?= htmlspecialchars((string) $plat->description) ?></p>
                <div class="flex items-center justify-between">
                    <span class="font-extrabold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</span>
                    <div class="flex items-center gap-2">
                        <a href="/produits/<?= $plat->id ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 hover:border-primary hover:text-primary transition" title="Voir le détail">
                            <i class="fa-regular fa-eye text-sm"></i>
                        </a>
                        <button
                            onclick="<?= htmlspecialchars('ajouterAuPanier({ id: ' . $plat->id . ', nom: ' . json_encode($plat->libelle) . ', prix: ' . $plat->prix . ', image: ' . json_encode($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') . ' })', ENT_QUOTES, 'UTF-8') ?>"
                            class="px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                            Commander
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-[#F6EFE0] ml-[calc(50%_-_50vw)] mr-[calc(50%_-_50vw)] my-16">
    <div class="max-w-7xl mx-auto px-6 py-14 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <span class="inline-block bg-red-600 text-white text-xs font-bold uppercase tracking-wide px-3 py-1 rounded-full mb-4">L'Art &amp; la Tradition Teranga</span>
            <h2 class="text-2xl md:text-3xl font-extrabold mb-4">Une cuisine authentique, mijotée avec générosité</h2>
            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                Chez Saveur221, chaque marmite raconte une histoire. Nos poissons Thiof sont sélectionnés chaque matin
                sur la côte dakaroise, nos viandes marinées aux herbes fraîches et grillées au bois d'acacia.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <i class="fa-solid fa-fire text-primary mb-2"></i>
                    <strong class="block text-sm mb-1">Le Véritable Roff</strong>
                    <span class="text-xs text-gray-500">Thiof entier mijoté dans sa sauce roff.</span>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <i class="fa-solid fa-fish text-amber-600 mb-2"></i>
                    <strong class="block text-sm mb-1">Accompagnement</strong>
                    <span class="text-xs text-gray-500">Riz parfumé, légumes fondants &amp; piments.</span>
                </div>
            </div>
        </div>
        <div class="relative">
            <img src="<?= $images['brochettes'] ?>" alt="Brochettes au feu de bois" class="rounded-2xl w-full h-80 md:h-[420px] object-cover shadow-xl">
            <div class="absolute bottom-4 left-4 bg-white rounded-lg px-4 py-3 shadow-lg">
                <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-hat-chef text-primary"></i> Préparé par nos Maîtres Cuisiniers
                </p>
            </div>
        </div>
    </div>
</section>

<section class="mb-14">
    <div class="flex items-end justify-between mb-6 flex-wrap gap-3">
        <div>
            <p class="text-primary font-bold text-xs uppercase tracking-wide mb-1">Témoignages clients</p>
            <h2 class="text-2xl font-extrabold">Ce que Dakar dit de Saveur221</h2>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" id="btn-avis-prev"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-primary hover:text-primary transition disabled:opacity-30 disabled:cursor-not-allowed"
                    aria-label="Avis précédents">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </button>
            <button type="button" id="btn-avis-next"
                    class="w-10 h-10 flex items-center justify-center rounded-full border border-gray-200 text-gray-500 hover:border-primary hover:text-primary transition disabled:opacity-30 disabled:cursor-not-allowed"
                    aria-label="Avis suivants">
                <i class="fa-solid fa-arrow-right text-sm"></i>
            </button>
        </div>
    </div>

    <?php if (empty($avis ?? [])): ?>
        <div class="border border-gray-100 rounded-xl p-10 text-center text-gray-400 text-sm">
            Aucun avis client pour le moment.
        </div>
    <?php else: ?>
    <div class="overflow-hidden -mx-2.5">
        <div id="piste-avis" class="flex transition-transform duration-500 ease-out cursor-grab active:cursor-grabbing">
            <?php foreach ($avis as $a): ?>
            <div class="w-full sm:w-1/2 lg:w-1/3 xl:w-1/4 shrink-0 px-2.5">
                <div class="border border-gray-100 rounded-xl p-5 hover:shadow-lg transition h-full flex flex-col">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-amber-500 text-sm"><?= str_repeat('★', $a->note) . str_repeat('☆', 5 - $a->note) ?></div>
                        <i class="fa-solid fa-quote-right text-primary/20 text-2xl"></i>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 flex-1">"<?= htmlspecialchars((string) $a->commentaire) ?>"</p>
                    <div class="pt-3 border-t border-gray-50">
                        <p class="font-semibold text-sm"><?= htmlspecialchars($a->clientPrenom . ' ' . $a->clientNom) ?></p>
                        <p class="text-xs text-gray-400">Client Saveur221</p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="points-avis" class="flex items-center justify-center gap-1.5 mt-5"></div>
    <?php endif; ?>
</section>

<script src="/assets/js/avis-slider.js"></script>

<section class="bg-primary-light/40 rounded-2xl p-10 mb-14">
    <h2 class="text-xl font-extrabold mb-1">Comment fonctionne Saveur221 ?</h2>
    <p class="text-sm text-gray-500 mb-6">Un processus simple, rapide et transparent en 3 étapes</p>
    <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ([
            ['Choisissez vos plats', 'Sélectionnez vos spécialités préférées et personnalisez vos instructions.'],
            ['Commandez & Réglez', 'Connectez-vous et réglez en toute sécurité via Wave, Orange Money.'],
            ['Retirez au comptoir', 'Votre commande vous attend bien chaude au comptoir de retrait.'],
        ] as $i => [$titre, $texte]): ?>
        <div class="bg-white rounded-xl p-5 shadow-sm">
            <span class="w-12 h-12 rounded-full text-white flex items-center justify-center font-extrabold text-lg mb-4 shadow-md" style="background-color:#B83518"><?= $i + 1 ?></span>
            <h3 class="font-bold text-sm mb-1"><?= $titre ?></h3>
            <p class="text-xs text-gray-500"><?= $texte ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-gray-900 rounded-2xl p-10 text-center mb-16">
    <h2 class="text-white text-2xl font-extrabold mb-3">Une envie soudaine de bon Thieb ou de Dibi chaud ?</h2>
    <p class="text-gray-300 text-sm mb-6">Passez votre commande en ligne en moins de 2 minutes.</p>
    <div class="flex flex-wrap items-center justify-center gap-3">
        <a href="/catalogue" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
            <i class="fa-solid fa-utensils"></i> Parcourir le catalogue
        </a>
        <a href="tel:+221785405593" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-black border border-white text-white font-semibold hover:bg-white/10 transition">
            <i class="fa-solid fa-phone"></i> Appeler le restaurant (+221 78 540 55 93)
        </a>
    </div>
</section>