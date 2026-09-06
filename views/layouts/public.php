<?php
/** @var string $content */
$user = $_SESSION['user'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars((string) $title) . ' - ' : '' ?>Saveur 221</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#B83518', dark: '#8f2913', light: '#FDEEE9' },
                        bgdash: '#F0F6FF',
                    },
                    fontFamily: { sans: ['Open Sans', 'sans-serif'] },
                },
            },
        };
    </script>
</head>
<body class="font-sans text-gray-800 bg-white">

<div class="max-w-7xl mx-auto px-6 pt-20">

    <header class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-5">
    <a href="/" class="flex items-center gap-2 text-xl font-extrabold">
        <span class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white">
            <i class="fa-solid fa-utensils"></i>
        </span>
        Saveur <span class="text-primary">221</span>
    </a>
    <nav class="hidden md:flex items-center gap-8 font-semibold text-sm">
        <a href="/" class="hover:text-primary transition">Accueil</a>
        <a href="/produits" class="hover:text-primary transition">Catalogues & Menus</a>
    </nav>
    <div class="flex items-center gap-3">
        <?php if ($user && in_array($user['role'], ['GERANT', 'ADMIN'], true)): ?>
            <a href="/produits" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition flex items-center gap-2">
                <i class="fa-solid fa-table-cells"></i> Espace <?= $user['role'] === 'ADMIN' ? 'Admin' : 'Gerant' ?>
            </a>
        <?php endif; ?>
        <?php if ($user && $user['role'] === 'CLIENT'): ?>
            <a href="#" class="px-4 py-2 rounded-lg bg-primary-light text-primary text-sm font-semibold flex items-center gap-2">
                <i class="fa-solid fa-bag-shopping"></i> Mon Panier
            </a>
        <?php endif; ?>
        <?php if ($user): ?>
            <span class="text-sm font-semibold hidden sm:inline"><?= htmlspecialchars($user['prenom']) ?></span>
            <a href="/deconnexion" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Deconnexion
            </a>
        <?php else: ?>
            <a href="/connexion" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold hover:border-primary hover:text-primary transition">Connexion</a>
            <a href="/inscription" class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition">Creer un compte</a>
        <?php endif; ?>
    </div>
    </div>
</header>

    <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
        <div class="mt-5 px-4 py-3 rounded-lg text-sm font-semibold <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?= $content ?>
</div>

<footer class="bg-gray-900 text-gray-400 mt-16 py-12">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
        <div>
            <div class="flex items-center gap-2 text-white font-extrabold text-lg mb-3">
                <span class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-utensils text-sm"></i>
                </span>
                Saveur <span class="text-primary">221</span>
            </div>
            <p class="text-sm mb-3">La haute gastronomie senegalaise preparee avec passion. Ingredients frais locaux, cuisson au feu de bois.</p>
            <a href="/inscription" class="text-sm font-semibold text-primary hover:underline">Devenir client</a>
        </div>
        <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Navigation</h4>
            <nav class="space-y-2.5 text-sm">
                <a href="/" class="flex items-center gap-2 hover:text-white transition"><i class="fa-solid fa-angle-right text-primary text-xs"></i> Accueil</a>
                <a href="/produits" class="flex items-center gap-2 hover:text-white transition"><i class="fa-solid fa-angle-right text-primary text-xs"></i> Notre carte &amp; menus</a>
                <a href="/connexion" class="flex items-center gap-2 hover:text-white transition"><i class="fa-solid fa-angle-right text-primary text-xs"></i> Connexion</a>
                <a href="/inscription" class="flex items-center gap-2 hover:text-white transition"><i class="fa-solid fa-angle-right text-primary text-xs"></i> Creer un compte</a>
            </nav>
        </div>
        <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Acces direct</h4>
            <ul class="space-y-2.5 text-sm">
                <li class="flex items-start gap-2.5"><i class="fa-solid fa-location-dot text-primary mt-0.5"></i> Route des Almadies, Dakar, Senegal</li>
                <li class="flex items-start gap-2.5"><i class="fa-solid fa-phone text-primary mt-0.5"></i> +221 78 540 55 93</li>
                <li class="flex items-start gap-2.5"><i class="fa-solid fa-clock text-primary mt-0.5"></i> Ouvert 7j/7 de 11h30 a 23h30</li>
            </ul>
            <h5 class="text-xs font-bold uppercase tracking-widest text-gray-500 mt-5 mb-2">Modalité</h5>
            <p class="text-sm">Commande en ligne&nbsp;: retrait au comptoir ou livraison.</p>
        </div>
        <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-gray-500 mb-4">Moyens de paiement</h4>
            <div class="flex flex-col gap-3 mt-3">
                <!-- Wave -->
                <div class="flex items-center gap-3">
                    <div class="bg-white/95 p-1 rounded flex items-center justify-center w-9 h-6 shadow-sm shrink-0">
                        <img src="/assets/img/paiements/wave.jpg" class="h-full w-full object-contain" alt="Wave">
                    </div>
                    <span class="text-white text-sm font-medium">Wave</span>
                </div>

                <!-- Orange Money -->
                <div class="flex items-center gap-3">
                    <div class="bg-white/95 p-1 rounded flex items-center justify-center w-9 h-6 shadow-sm shrink-0">
                        <img src="/assets/img/paiements/om.png" class="h-full w-full object-contain" alt="Orange Money">
                    </div>
                    <span class="text-white text-sm font-medium">Orange Money</span>
                </div>

                <!-- Espèces -->
                <div class="flex items-center gap-3">
                    <div class="bg-white/95 p-1 rounded flex items-center justify-center w-9 h-6 shadow-sm shrink-0">
                        <img src="/assets/img/paiements/especes.jpg" class="h-full w-full object-contain" alt="Espèces">
                    </div>
                    <span class="text-white text-sm font-medium">Espèces</span>
                </div>

                <!-- Carte Bancaire -->
                <div class="flex items-center gap-3">
                    <div class="bg-white/95 p-1 rounded flex items-center justify-center w-9 h-6 shadow-sm shrink-0">
                        <img src="/assets/img/paiements/carte.jpg" class="h-full w-full object-contain" alt="Carte Bancaire">
                    </div>
                    <span class="text-white text-sm font-medium">Carte Bancaire</span>
                </div>
            </div>
        </div>
    </div>
</footer>

</body>
</html>