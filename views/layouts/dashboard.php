<?php
/** @var string $content */
$user = $_SESSION['user'] ?? null;
$role = $user['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars((string) $title) . ' - ' : '' ?>Saveur 221</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: { primary: { DEFAULT: '#B83518', dark: '#8f2913', light: '#FDEEE9' }, bgdash: '#F0F6FF' },
                fontFamily: { sans: ['Open Sans', 'sans-serif'] },
            } },
        };
    </script>
</head>
<body class="font-sans bg-bgdash text-gray-800 flex min-h-screen">

<aside class="w-64 bg-gray-950 text-gray-300 flex flex-col shrink-0">
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-2 text-white font-extrabold text-lg">
            <span class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center">
                <i class="fa-solid fa-utensils text-sm"></i>
            </span>
            Saveur <span class="text-primary">221</span>
        </div>
    </div>

    <nav class="flex-1 py-4 px-3 space-y-1 text-sm font-semibold">
        <?php
        $lien = fn (string $href, string $icone, string $label) => sprintf(
            '<a href="%s" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition %s">
                <i class="fa-solid %s w-4"></i> %s
            </a>',
            $href, $icone, str_contains($_SERVER['REQUEST_URI'], $href) && $href !== '/'
                ? 'bg-primary text-white'
                : 'hover:bg-white/5 hover:text-white',
            $label
        );
        echo $lien('/dashboard', 'fa-table-cells', 'Tableau de Bord');
        echo $lien('/commandes', 'fa-receipt', 'Commandes en direct');
        echo $lien('/produits', 'fa-utensils', 'Plats & Menus');
        echo $lien('/categories', 'fa-layer-group', 'Categories');
        echo $lien('/stocks', 'fa-boxes-stacked', 'Gestion des Stocks');
        echo $lien('/paiements', 'fa-credit-card', 'Caisse & Reglements');
        echo $lien('/statistiques', 'fa-chart-line', 'Rapports & Statistiques');
        if ($role === 'ADMIN'):
            echo $lien('/clients', 'fa-users', 'Clients');
            echo $lien('/avis', 'fa-star', 'Avis');
            echo $lien('/staff', 'fa-user-group', 'Equipe Staff');
        endif;
        echo $lien('/profil', 'fa-user', 'Mon Profil & Securite');
        ?>
    </nav>

    <div class="p-3 border-t border-white/10 space-y-1 text-sm font-semibold">
        <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-amber-400 hover:bg-white/5 transition">
            <i class="fa-solid fa-globe w-4"></i> Voir le site public
        </a>
        <a href="/deconnexion" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-white/5 transition">
            <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Deconnexion
        </a>
    </div>
</aside>

<div class="flex-1 flex flex-col min-w-0">
    <header class="bg-gray-950 text-white px-8 py-4 flex items-center justify-between">
        <div>
            <h1 class="text-lg font-bold">Saveur <span class="text-primary">221</span></h1>
            <p class="text-xs text-gray-400">SAVEURS AUTHENTIQUES DU SENEGAL</p>
        </div>
        <div class="flex items-center gap-5">
            <button id="btn-notifications" class="relative text-gray-300 hover:text-white transition">
                <i class="fa-solid fa-bell text-lg"></i>
                <span id="badge-notifications" class="hidden absolute -top-1.5 -right-1.5 bg-primary text-[10px] w-4 h-4 rounded-full flex items-center justify-center"></span>
            </button>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-primary flex items-center justify-center font-bold text-sm">
                    <?= htmlspecialchars(mb_substr($user['prenom'] ?? '?', 0, 1)) ?>
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-semibold leading-none"><?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></p>
                    <p class="text-xs text-gray-400"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 p-8">
        <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
            <div class="mb-6 px-4 py-3 rounded-lg text-sm font-semibold <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>
</div>

<script src="/assets/js/notifications.js"></script>
</body>
</html>