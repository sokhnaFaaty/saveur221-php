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

<div class="max-w-7xl mx-auto px-6">

    <header class="flex items-center justify-between py-5 border-b border-gray-100">
        <a href="/" class="flex items-center gap-2 text-xl font-extrabold">
            <span class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center text-white">S</span>
            Saveur <span class="text-primary">221</span>
        </a>
        <nav class="hidden md:flex items-center gap-8 font-semibold text-sm">
            <a href="/" class="hover:text-primary transition">Accueil</a>
            <a href="/produits" class="hover:text-primary transition">Catalogues & Menus</a>
        </nav>
        <div class="flex items-center gap-3">
            <?php if ($user && in_array($user['role'], ['GERANT', 'ADMIN'], true)): ?>
                <a href="/produits" class="px-4 py-2 rounded-lg bg-gray-900 text-white text-sm font-semibold hover:bg-gray-800 transition">
                    Espace <?= $user['role'] === 'ADMIN' ? 'Admin' : 'Gerant' ?>
                </a>
            <?php endif; ?>
            <?php if ($user && $user['role'] === 'CLIENT'): ?>
                <a href="#" class="px-4 py-2 rounded-lg bg-primary-light text-primary text-sm font-semibold">Mon Panier</a>
            <?php endif; ?>
            <?php if ($user): ?>
                <span class="text-sm font-semibold hidden sm:inline"><?= htmlspecialchars($user['prenom']) ?></span>
                <a href="/deconnexion" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold hover:border-primary hover:text-primary transition">Deconnexion</a>
            <?php else: ?>
                <a href="/connexion" class="px-4 py-2 rounded-lg border border-gray-200 text-sm font-semibold hover:border-primary hover:text-primary transition">Connexion</a>
                <a href="/inscription" class="px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition">Creer un compte</a>
            <?php endif; ?>
        </div>
    </header>

    <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
        <div class="mt-5 px-4 py-3 rounded-lg text-sm font-semibold <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
    <?php endif; ?>

    <?= $content ?>
</div>

<footer class="bg-gray-900 text-gray-300 mt-16 py-12">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-4 gap-10">
        <div>
            <div class="flex items-center gap-2 text-white font-extrabold text-lg mb-3">
                <span class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">S</span>
                Saveur <span class="text-primary">221</span>
            </div>
            <p class="text-sm">La haute gastronomie senegalaise preparee avec passion. Ingredients frais locaux, cuisson au feu de bois.</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3 text-sm">Navigation</h4>
            <a href="/" class="block text-sm mb-2 hover:text-primary transition">Accueil</a>
            <a href="/produits" class="block text-sm mb-2 hover:text-primary transition">Notre carte & menus</a>
            <a href="/connexion" class="block text-sm mb-2 hover:text-primary transition">Connexion</a>
            <a href="/inscription" class="block text-sm mb-2 hover:text-primary transition">Creer un compte</a>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3 text-sm">Acces direct</h4>
            <p class="text-sm mb-2">Route des Almadies, Dakar</p>
            <p class="text-sm mb-2">Ouvert 7j/7 de 11h30 a 23h30</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3 text-sm">Moyens de paiement</h4>
            <p class="text-sm mb-2">Wave - Orange Money - Especes</p>
        </div>
    </div>
</footer>

</body>
</html>