<?php
/** @var \App\Models\Commande[] $dernieresCommandes */
/** @var \App\Models\Produit[] $alertesStock */
$user = $_SESSION['user'];
?>
<h1 class="text-2xl font-extrabold mb-1">Bienvenue, <?= htmlspecialchars($user['prenom']) ?> !</h1>
<p class="text-gray-500 text-sm mb-8">Apercu en temps reel de l'activite du restaurant et de la caisse</p>

<div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
    <?php foreach ([
        ['fa-sack-dollar', 'text-green-600 bg-green-50', 'border-green-400', 'CHIFFRE D\'AFFAIRES', number_format($chiffreAffaires, 0) . ' FCFA'],
        ['fa-clock', 'text-blue-600 bg-blue-50', 'border-blue-400', 'COMMANDES EN COURS', (string) $commandesEnCours],
        ['fa-triangle-exclamation', 'text-red-600 bg-red-50', 'border-red-400', 'ALERTES STOCK', count($alertesStock) . ' plat(s)'],
        ['fa-star', 'text-amber-600 bg-amber-50', 'border-amber-400', 'NOTE MOYENNE', '- /5.0'],
    ] as [$icone, $couleur, $bordure, $label, $valeur]): ?>
    <div class="bg-white rounded-xl p-5 border-t-4 <?= $bordure ?> shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs font-bold text-gray-500"><?= $label ?></span>
            <span class="w-8 h-8 rounded-lg flex items-center justify-center <?= $couleur ?>"><i class="fa-solid <?= $icone ?> text-sm"></i></span>
        </div>
        <p class="text-xl font-extrabold"><?= $valeur ?></p>
    </div>
    <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-bold">Dernieres Commandes Clients</h2>
            <a href="/commandes" class="text-primary text-sm font-semibold hover:underline">Gerer toutes les commandes</a>
        </div>
        <?php foreach ($dernieresCommandes as $commande): ?>
        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
            <div>
                <p class="font-semibold text-sm"><?= htmlspecialchars($commande->numCommande) ?></p>
                <p class="text-xs text-gray-400"><?= count($commande->lignes) ?> article(s)</p>
            </div>
            <div class="text-right">
                <p class="font-bold text-sm text-primary"><?= number_format($commande->total, 0) ?> FCFA</p>
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600"><?= htmlspecialchars($commande->statut) ?></span>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if ($dernieresCommandes === []): ?>
            <p class="text-center text-gray-400 py-8 text-sm">Aucune commande pour le moment.</p>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h2 class="font-bold mb-4">Acces Rapides</h2>
        <div class="space-y-3">
            <a href="/produits" class="flex items-center justify-between px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm font-semibold">
                Ajouter ou modifier un plat <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
            <a href="/stocks" class="flex items-center justify-between px-4 py-3 rounded-lg bg-orange-50 hover:bg-orange-100 transition text-sm font-semibold text-orange-700">
                Reapprovisionner le stock <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
            <a href="/paiements" class="flex items-center justify-between px-4 py-3 rounded-lg bg-green-50 hover:bg-green-100 transition text-sm font-semibold text-green-700">
                Enregistrer un encaissement <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
        </div>
    </div>
</div>