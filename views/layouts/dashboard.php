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
                colors: { primary: { DEFAULT: '#A8291A', dark: '#8A2013', light: '#FBECEA' }, bgdash: '#F0F6FF' },
            fontFamily: { sans: ['Open Sans', 'sans-serif'] },
            } } },
        };
    </script>
    <style>
        /* Masque toutes les scrollbars (page + blocs internes) tout en gardant le scroll */
        * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { width: 0; height: 0; display: none; }
    </style>
</head>

<div id="modal-confirmation" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-start gap-3 mb-4">
            <span class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                <i class="fa-regular fa-trash-can"></i>
            </span>
            <div>
                <h3 data-titre class="font-bold"></h3>
                <p class="text-xs text-gray-400">Attention : cette action nécessite votre confirmation</p>
            </div>
        </div>
        <p data-message class="text-sm text-gray-600 mb-3"></p>
        <div class="bg-gray-50 rounded-lg px-3 py-2 mb-5 text-sm">
            <p class="text-xs text-gray-400 font-bold">ÉLÉMENT CIBLÉ</p>
            <p data-cible class="font-semibold"></p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="fermerConfirmation()" class="flex-1 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">Annuler</button>
            <form method="post" class="flex-1">
                <button type="submit" class="w-full py-2.5 rounded-lg bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition">Supprimer</button>
            </form>
        </div>
    </div>
</div>
<script src="/assets/js/confirm-modal.js"></script>
<body class="font-sans bg-[#F0F6FF] text-gray-800 flex h-screen overflow-hidden">

<aside class="hidden md:flex w-64 bg-gray-950 text-gray-300 flex-col shrink-0">
    <div class="p-6 border-b border-white/10">
        <div class="flex items-center gap-3">
            <span class="w-10 h-10 bg-[#A8291A] rounded-xl flex items-center justify-center shrink-0">
                <i class="fa-solid fa-utensils text-white text-base"></i>
            </span>
            <div class="leading-tight">
                <p class="text-white font-extrabold text-lg">Saveur <span class="text-primary">221</span></p>
                <p class="text-[10px] font-semibold tracking-wider text-amber-400">SAVEURS AUTHENTIQUES DU SENEGAL</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 min-h-0 py-4 px-3 space-y-1 text-sm font-semibold overflow-y-auto">
        <?php
        $cheminActuel = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $estActif = fn (string $href) => $cheminActuel === $href || str_starts_with($cheminActuel, $href . '/');
        $lien = fn (string $href, string $icone, string $label) => sprintf(
            '<a href="%s" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition %s">
                <i class="fa-solid %s w-4"></i> %s
            </a>',
            $href, $estActif($href) ? 'bg-[#A8291A] text-white shadow-sm' : 'hover:bg-white/5 hover:text-white',
            $icone, $label
        );
        echo $lien('/dashboard', 'fa-table-cells', 'Tableau de Bord');
        echo $lien('/commandes', 'fa-receipt', 'Commandes en direct');
        echo $lien('/produits', 'fa-utensils', 'Plats & Menus');
        echo $lien('/categories', 'fa-layer-group', 'Catégories');
        echo $lien('/stocks', 'fa-boxes-stacked', 'Gestion des Stocks');
        echo $lien('/paiements', 'fa-credit-card', 'Caisse & Règlements');
        echo $lien('/statistiques', 'fa-chart-line', 'Rapports & Statistiques');
        if ($role === 'ADMIN'):
            echo $lien('/clients', 'fa-users', 'Clients');
            echo $lien('/avis', 'fa-star', 'Avis');
            echo $lien('/staff', 'fa-user-group', 'Équipe Staff');
        endif;
        echo $lien('/profil', 'fa-user', 'Mon Profil & Sécurité');
        ?>
    </nav>

    <div class="p-4 pb-6 border-t border-white/10 space-y-1.5 text-sm font-semibold">
        <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-amber-400 hover:bg-white/5 transition">
            <i class="fa-solid fa-globe w-4"></i> Voir le site public
        </a>
        <a href="/deconnexion" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-400 hover:bg-white/5 transition">
            <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Déconnexion
        </a>
    </div>
</aside>

<div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
    <!-- Header mobile : coin superieur -->
    <header class="bg-gray-950 text-white px-8 py-4 flex items-center justify-between">
        <h1 class="text-lg font-bold">Saveur <span class="text-primary">221</span></h1>
        <div class="flex items-center gap-3.5">
            <!-- Cloche de notifications -->
            <div class="relative">
                <button type="button" id="btn-notifications" onclick="basculerNotifications()" class="relative w-10 h-10 rounded-lg bg-white/10 hover:bg-white/20 transition flex items-center justify-center" aria-label="Notifications">
                    <i class="fa-regular fa-bell text-white text-base"></i>
                    <span id="badge-notifications" class="hidden absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center"></span>
                </button>

                <div id="panneau-notifications" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                    <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-bold text-gray-900">Notifications</p>
                        <span id="nb-notifications-lues" class="text-[11px] text-gray-400"></span>
                    </div>
                    <div id="liste-notifications" class="max-h-80 overflow-y-auto"></div>
                </div>
            </div>

            <?php $avatar = $user['image'] ?? ''; ?>
            <div class="w-10 h-10 rounded-full overflow-hidden bg-[#A8291A] flex items-center justify-center shrink-0">
                <?php if ($avatar !== ''): ?>
                    <img src="<?= htmlspecialchars($avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <i class="fa-solid fa-user text-white text-sm"></i>
                <?php endif; ?>
            </div>
            <div class="flex flex-col justify-center leading-tight">
                <p class="text-sm font-semibold"><?= htmlspecialchars(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? '')) ?></p>
                <p class="text-[11px] text-gray-400"><?= htmlspecialchars($role === 'GERANT' ? 'Gérant' : ($role === 'ADMIN' ? 'Administrateur' : ($role ?? ''))) ?></p>
            </div>
        </div>
    </header>

    <main class="flex-1 min-h-0 p-4 md:p-8 pb-24 md:pb-8 overflow-y-auto">
        <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
            <div class="mb-6 px-4 py-3 rounded-lg text-sm font-semibold <?= $flash['type'] === 'success' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' ?>">
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>
</div>

<script src="/assets/js/notification.js"></script>

<!-- Bottom Bar mobile (remplace le menu hamburger / la sidebar) -->
<nav class="md:hidden fixed bottom-0 inset-x-0 z-50 bg-white border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.08)] px-4 pb-[env(safe-area-inset-bottom)]">
    <div class="flex items-center justify-between py-2">
        <a href="/produits" class="flex flex-col items-center gap-1 text-[11px] font-semibold <?= $estActif('/produits') ? 'text-primary' : 'text-gray-600 hover:text-primary' ?> transition">
            <span class="w-10 h-10 rounded-full bg-primary-light text-primary flex items-center justify-center">
                <i class="fa-solid fa-utensils"></i>
            </span>
            Plats / Menus
        </a>

        <div class="relative">
            <button id="btn-plus-mobile" onclick="basculerCentreControle()" class="flex flex-col items-center gap-1 text-[11px] font-semibold text-gray-600 hover:text-primary transition">
                <span class="-mt-6 w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center shadow-lg ring-4 ring-primary-light">
                    <i id="icone-plus-mobile" class="fa-solid fa-plus text-xl"></i>
                </span>
                <span class="text-primary font-bold">Plus</span>
            </button>

            <div id="centre-controle" class="hidden absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 p-3 space-y-1">
                <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 px-2 pb-1">Centre de contrôle</p>
                <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold <?= $estActif('/dashboard') ? 'bg-primary-light text-primary' : 'text-gray-700 hover:bg-gray-50' ?>"><i class="fa-solid fa-table-cells w-4 text-primary"></i> Tableau de bord</a>
                <a href="/stocks" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold <?= $estActif('/stocks') ? 'bg-primary-light text-primary' : 'text-gray-700 hover:bg-gray-50' ?>"><i class="fa-solid fa-boxes-stacked w-4 text-primary"></i> Gestion des stocks</a>
                <a href="/commandes" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold <?= $estActif('/commandes') ? 'bg-primary-light text-primary' : 'text-gray-700 hover:bg-gray-50' ?>"><i class="fa-solid fa-receipt w-4 text-primary"></i> Commandes en direct</a>
                <?php if ($role === 'ADMIN'): ?>
                <a href="/staff" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold <?= $estActif('/staff') ? 'bg-primary-light text-primary' : 'text-gray-700 hover:bg-gray-50' ?>"><i class="fa-solid fa-user-group w-4 text-primary"></i> Équipe Staff</a>
                <?php endif; ?>
                <a href="/" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50"><i class="fa-solid fa-globe w-4 text-primary"></i> Voir le site public</a>
                <a href="/deconnexion" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50"><i class="fa-solid fa-arrow-right-from-bracket w-4 text-primary"></i> Déconnexion</a>
            </div>
        </div>

        <a href="/profil" class="flex flex-col items-center gap-1 text-[11px] font-semibold <?= $estActif('/profil') ? 'text-primary' : 'text-gray-600 hover:text-primary' ?> transition">
            <span class="w-10 h-10 rounded-full bg-primary-light text-primary flex items-center justify-center">
                <i class="fa-solid fa-user-shield"></i>
            </span>
            Mon Profil & Sécurité
        </a>
    </div>
</nav>

<script>
    function basculerCentreControle() {
        const panneau = document.getElementById('centre-controle');
        const icone = document.getElementById('icone-plus-mobile');
        const ouvre = panneau.classList.toggle('hidden');
        icone.classList.toggle('fa-xmark', !ouvre);
        icone.classList.toggle('fa-plus', ouvre);
    }
</script>
</body>
</html>