<?php
/** @var \App\Models\Categorie[] $categories */
/** @var \App\Models\Produit[] $plats */

$images = [
    'hero'          => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788727588/saveur221/images/cotelettes-grillees.jpg',
    'grillade'      => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725699/saveur221/images/grillade-dibiterie.jpg',
    'thieboudienne' => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725692/saveur221/images/thieboudienne-rouge.jpg',
    'brochettes'    => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725696/saveur221/images/brochette-dibi.jpg',
    'categories'    => [
        1 => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725692/saveur221/images/thieboudienne-rouge.jpg',
        2 => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788725699/saveur221/images/grillade-dibiterie.jpg',
        3 => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788726508/saveur221/images/thiakry-dessert.jpg',
        4 => 'https://res.cloudinary.com/djh0kp7rv/image/upload/v1788726510/saveur221/images/bissap-boisson.jpg',
    ],
];
?>

<section class="relative overflow-hidden ml-[calc(50%_-_50vw)] mr-[calc(50%_-_50vw)]">
    <div class="relative h-[550px] bg-cover bg-center" style="background-image:url('<?= $images['hero'] ?>')">
        <div class="absolute inset-0 bg-black/55"></div>
        <div class="relative h-full grid md:grid-cols-[1.4fr,1fr] gap-10 items-center p-10 md:p-16">
            <div class="text-white">
                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-4">
                    La Haute Gastronomie <span class="text-primary">Senegalaise</span> chez Vous
                </h1>
                <p class="text-gray-200 mb-6 max-w-md">
                    Degustez nos recettes emblematiques mijotees dans le respect des traditions :
                    Thieboudiene Penda Mbaye au Thiof frais, Yassa au Poulet braise, Dibi d'agneau au feu de bois.
                </p>
                <div class="flex flex-wrap gap-3 mb-6">
                    <a href="/produits" class="px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
                        Commander maintenant
                    </a>
                    <a href="#incontournables" class="px-6 py-3 rounded-lg border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                        Decouvrez le Thieboudiene du Chef
                    </a>
                </div>
                <div class="flex flex-wrap gap-5 text-sm text-gray-300">
                    <span>100% Ingredients Locaux Frais</span>
                    <span>Paiement Wave et OM 0% frais</span>
                    <span>Emballages thermo-scelles</span>
                </div>
            </div>

            <?php if (!empty($plats[0])): $vedette = $plats[0]; ?>
            <div class="z-10 rounded-2xl overflow-hidden shadow-2xl ring-1 ring-white/10 bg-[#111827]/90 backdrop-blur-md">
                <div class="relative h-48">
                    <img src="<?= htmlspecialchars((string) ($vedette->image ?: $images['thieboudienne'])) ?>" alt="Thieboudienne Penda Mbaye" class="w-full h-full object-cover">
                    <span class="absolute top-3 right-3 flex items-center gap-1.5 bg-white/95 text-amber-500 text-sm font-bold px-2.5 py-1 rounded-full shadow-lg">
                        <i class="fa-solid fa-star"></i> 4.9/5
                    </span>
                </div>
                <div class="p-5 text-white">
                    <span class="inline-block bg-primary text-white text-xs px-3 py-1 rounded-md mb-3">Plat Signature</span>
                    <h3 class="font-bold text-lg mb-1">Thieboudienne Penda Mbaye</h3>
                    <p class="text-sm text-gray-300 mb-4"><?= htmlspecialchars((string) $vedette->description) ?></p>
                    <div class="flex items-center justify-between">
                        <span class="text-primary font-extrabold text-xl"><?= number_format($vedette->prix, 0) ?> FCFA</span>
                        <button class="px-4 py-2 bg-primary text-white rounded-lg text-sm font-semibold hover:bg-primary-dark transition">Ajouter</button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="grid grid-cols-2 md:grid-cols-4 gap-6 my-12">
    <?php foreach ([
        ['fa-fire', 'Braise Artisanale', 'Cuisson au feu de bois', 'bg-red-50', 'text-red-700', 'text-red-700'],
        ['fa-clock', '30 a 45 Minutes', 'Livraison rapide Dakar', 'bg-amber-50', 'text-amber-500', ''],
        ['fa-shield-halved', 'Wave & OM 0% Frais', 'Paiement 100% Securise', 'bg-green-50', 'text-green-600', ''],
        ['fa-box', 'Chaud & Hermetique', 'Conditionnement isotherme', 'bg-gray-50', 'text-gray-700', ''],
    ] as [$icone, $titre, $sous, $fond, $couleurIcone, $couleurTitre]): ?>
    <div class="flex gap-3 bg-white rounded-xl p-4 shadow-md">
        <div class="w-11 h-11 rounded-lg <?= $fond ?> flex items-center justify-center <?= $couleurIcone ?> shrink-0">
            <i class="fa-solid <?= $icone ?>"></i>
        </div>
        <div>
            <strong class="block text-sm <?= $couleurTitre ?>"><?= $titre ?></strong>
            <span class="text-xs text-gray-500"><?= $sous ?></span>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<section class="mb-14">
    <p class="text-primary font-bold text-xs uppercase tracking-wide mb-1">Explorez notre carte</p>
    <h2 class="text-2xl font-extrabold mb-6">Categories de Plats & Specialites</h2>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-5">
        <?php foreach ($categories as $categorie): ?>
        <a href="/produits?categorie=<?= $categorie->id ?>" class="group rounded-xl overflow-hidden border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300">
            <div class="h-32 overflow-hidden">
                <img src="<?= htmlspecialchars($images['categories'][$categorie->id] ?? $images['grillade']) ?>" alt=""
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
            <div class="p-3">
                <h3 class="font-semibold text-sm"><?= htmlspecialchars($categorie->libelle) ?></h3>
                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars((string) $categorie->description) ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</section>

<section id="incontournables" class="mb-14">
    <p class="text-primary font-bold text-xs uppercase tracking-wide mb-1">Les incontournables</p>
    <h2 class="text-2xl font-extrabold mb-6">Plats Coup de Coeur de Dakar</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach (array_slice($plats, 0, 4) as $plat): ?>
        <div class="group rounded-xl border border-gray-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition duration-300">
            <div class="relative h-36 overflow-hidden">
                <img src="<?= htmlspecialchars($plat->image ?: $images['thieboudienne']) ?>" alt="<?= htmlspecialchars($plat->libelle) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-2 left-2 bg-white text-[11px] font-bold px-2 py-1 rounded">
                    <?= htmlspecialchars((string) $plat->categorieLibelle) ?>
                </span>
                <span class="absolute top-2 right-2 text-[11px] font-bold px-2 py-1 rounded
                    <?= $plat->disponible() ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
                    <?= $plat->disponible() ? 'En stock' : 'Epuise' ?>
                </span>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-sm mb-1"><?= htmlspecialchars($plat->libelle) ?></h3>
                <p class="text-xs text-gray-500 mb-3 line-clamp-2"><?= htmlspecialchars((string) $plat->description) ?></p>
                <div class="flex items-center justify-between">
                    <span class="font-extrabold text-primary"><?= number_format($plat->prix, 0) ?> FCFA</span>
                    <a href="/produits/<?= $plat->id ?>" class="px-3 py-1.5 rounded-lg bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition">
                        Commander
                    </a>
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
            <h2 class="text-2xl md:text-3xl font-extrabold mb-4">Une cuisine authentique, mijotee avec generosite</h2>
            <p class="text-gray-600 text-sm leading-relaxed mb-6">
                Chez Saveur221, chaque marmite raconte une histoire. Nos poissons Thiof sont selectionnes chaque matin
                sur la cote dakaroise, nos viandes marinees aux herbes fraiches et grillees au bois d'acacia.
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <i class="fa-solid fa-fire text-primary mb-2"></i>
                    <strong class="block text-sm mb-1">Le Veritable Roff</strong>
                    <span class="text-xs text-gray-500">Thiof entier mijote dans sa sauce roff.</span>
                </div>
                <div class="bg-white rounded-xl p-4 shadow-sm">
                    <i class="fa-solid fa-fish text-amber-600 mb-2"></i>
                    <strong class="block text-sm mb-1">Accompagnement</strong>
                    <span class="text-xs text-gray-500">Riz parfume, legumes fondants &amp; piments.</span>
                </div>
            </div>
        </div>
        <div class="relative">
            <img src="<?= $images['brochettes'] ?>" alt="Brochettes au feu de bois" class="rounded-2xl w-full h-80 md:h-[420px] object-cover shadow-xl">
            <div class="absolute bottom-4 left-4 bg-white rounded-lg px-4 py-3 shadow-lg">
                <p class="text-sm font-bold text-gray-900 flex items-center gap-2">
                    <i class="fa-solid fa-hat-chef text-primary"></i> Prepare par nos Maitres Cuisiniers
                </p>
            </div>
        </div>
    </div>
</section>

<section class="mb-14">
    <p class="text-primary font-bold text-xs uppercase tracking-wide mb-1">Temoignages clients</p>
    <h2 class="text-2xl font-extrabold mb-6">Ce que Dakar dit de Saveur221</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php foreach ($avis ?? [] as $a): ?>
        <div class="border border-gray-100 rounded-xl p-5 hover:shadow-lg transition">
            <div class="text-amber-500 text-sm mb-2"><?= str_repeat('★', $a->note) . str_repeat('☆', 5 - $a->note) ?></div>
            <p class="text-sm text-gray-600 mb-3">"<?= htmlspecialchars((string) $a->commentaire) ?>"</p>
            <p class="font-semibold text-sm"><?= htmlspecialchars($a->clientPrenom . ' ' . $a->clientNom) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-primary-light/40 rounded-2xl p-10 mb-14">
    <h2 class="text-xl font-extrabold mb-1">Comment fonctionne Saveur221 ?</h2>
    <p class="text-sm text-gray-500 mb-6">Un processus simple, rapide et transparent en 3 etapes</p>
    <div class="grid md:grid-cols-3 gap-6">
        <?php foreach ([
            ['Choisissez vos plats', 'Selectionnez vos specialites preferees et personnalisez vos instructions.'],
            ['Commandez & Reglez', 'Connectez-vous et reglez en toute securite via Wave, Orange Money.'],
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
        <a href="/produits" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
            <i class="fa-solid fa-utensils"></i> Parcourir le catalogue
        </a>
        <a href="tel:+221785405593" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-black border border-white text-white font-semibold hover:bg-white/10 transition">
            <i class="fa-solid fa-phone"></i> Appeler le restaurant (+221 78 540 55 93)
        </a>
    </div>
</section>