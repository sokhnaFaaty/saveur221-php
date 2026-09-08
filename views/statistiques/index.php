<?php
/** @var float $chiffreAffaires */
/** @var int $nombreCommandes */
/** @var float $panierMoyen */
/** @var array<string, array{montant: float, pourcentage: float}> $repartitionMoyens */

$moyens = [
    'WAVE'         => ['libelle' => 'Wave Sénégal', 'icone' => 'fa-mobile-screen', 'couleur' => '#3B82F6'],
    'ORANGE_MONEY' => ['libelle' => 'Orange Money', 'icone' => 'fa-mobile', 'couleur' => '#F97316'],
    'ESPECES'      => ['libelle' => 'Espèces / Caisse', 'icone' => 'fa-money-bill', 'couleur' => '#22C55E'],
];
?>
<div class="-mx-6 bg-[#F0F6FF] min-h-screen px-4 md:px-8 py-8">

    <div class="max-w-7xl mx-auto">

        <!-- En-tête -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900">Rapports & Statistiques</h1>
                <p class="text-sm text-gray-500 mt-1">Suivi des ventes, répartition des encaissements et performances.</p>
            </div>
        </div>

        <!-- Cartes de statistiques globales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 mb-2">RECETTES GLOBALES</p>
                    <p class="text-2xl md:text-3xl font-extrabold text-green-600"><?= number_format($chiffreAffaires, 0, ' ', ' ') ?> FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Cumul des encaissements</p>
                </div>
                <span class="w-11 h-11 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-sack-dollar"></i>
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 mb-2">VOLUME DE COMMANDE</p>
                    <p class="text-2xl md:text-3xl font-extrabold text-gray-900"><?= $nombreCommandes ?></p>
                    <p class="text-xs text-gray-400 mt-1">Commandes reçues</p>
                </div>
                <span class="w-11 h-11 rounded-xl bg-primary-light text-primary flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-receipt"></i>
                </span>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-gray-500 mb-2">PANIER MOYEN</p>
                    <p class="text-2xl md:text-3xl font-extrabold text-amber-600"><?= number_format($panierMoyen, 0, ' ', ' ') ?> FCFA</p>
                    <p class="text-xs text-gray-400 mt-1">Par commande</p>
                </div>
                <span class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <i class="fa-solid fa-cart-shopping"></i>
                </span>
            </div>
        </div>

        <!-- Répartition par mode de paiement -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <h2 class="font-extrabold text-lg text-gray-900 mb-1">Répartition par mode de paiement</h2>
            <p class="text-sm text-gray-500 mb-6">D'où vient l'argent encaissé.</p>

            <div class="flex items-end md:items-center gap-4 md:gap-8">
                <div class="relative w-40 h-40 md:w-52 md:h-52 shrink-0">
                    <svg viewBox="0 0 36 36" class="w-full h-full -rotate-90">
                        <?php
                        $rayon = 15.915;
                        $circonference = 2 * M_PI * $rayon;
                        $offset = 25.0;
                        foreach ($repartitionMoyens as $moyen => $data) {
                            if ($data['pourcentage'] <= 0) {
                                continue;
                            }
                            $longueur = ($data['pourcentage'] / 100) * $circonference;
                            $style = sprintf('stroke-dasharray: %s %s; stroke-dashoffset: %s; stroke: %s;',
                                round($longueur, 3), round($circonference, 3), round($offset, 3), $moyens[$moyen]['couleur']);
                            echo '<circle cx="18" cy="18" r="' . $rayon . '" fill="none" stroke-width="4" style="' . $style . '"></circle>';
                            $offset -= $longueur;
                        }
                        ?>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <p class="text-2xl font-extrabold text-gray-900"><?= number_format($chiffreAffaires, 0, ' ', ' ') ?></p>
                        <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wide">FCFA</p>
                    </div>
                </div>

                <div class="flex-1 min-w-0 space-y-4">
                    <?php foreach ($repartitionMoyens as $moyen => $data): ?>
                        <?php $infos = $moyens[$moyen]; ?>
                        <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                            <span class="w-10 h-10 rounded-xl flex items-center justify-center text-white shrink-0"
                                  style="background-color: <?= $infos['couleur'] ?>">
                                <i class="fa-solid <?= $infos['icone'] ?>"></i>
                            </span>
                            <div class="flex-1 min-w-[140px]">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="text-sm font-semibold text-gray-800"><?= $infos['libelle'] ?></p>
                                    <p class="text-sm font-extrabold text-gray-900"><?= number_format($data['montant'], 0, ' ', ' ') ?> FCFA
                                        <span class="text-gray-400 font-semibold">(<?= str_replace('.', ',', (string) $data['pourcentage']) ?>%)</span>
                                    </p>
                                </div>
                                <div class="mt-1.5 h-2 rounded-full bg-gray-100 overflow-hidden">
                                    <div class="h-full rounded-full" style="width: <?= $data['pourcentage'] ?>%; background-color: <?= $infos['couleur'] ?>"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($chiffreAffaires <= 0): ?>
                        <p class="text-sm text-gray-400 py-4 text-center">Aucun encaissement pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>