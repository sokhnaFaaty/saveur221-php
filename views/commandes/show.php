<?php
/** @var \App\Models\Commande $commande */

$steps = [
    'EN_ATTENTE'     => 1,
    'EN_PREPARATION' => 2,
    'PRETE'          => 3,
    'RETIREE'        => 4,
];
$etapeCourante = $steps[$commande->statut] ?? 0;

$libelles = [
    'EN_ATTENTE'      => 'En attente de confirmation',
    'EN_PREPARATION'  => 'En préparation',
    'PRETE'           => 'Prête au comptoir',
    'RETIREE'         => 'Commande retirée',
    'ANNULEE'         => 'Commande annulée',
];

$couleursStatut = [
    'EN_ATTENTE'      => 'bg-amber-100 text-amber-800',
    'EN_PREPARATION'  => 'bg-blue-100 text-blue-800',
    'PRETE'           => 'bg-green-100 text-green-800',
    'RETIREE'         => 'bg-gray-900 text-white',
    'ANNULEE'         => 'bg-red-100 text-red-700',
];
?>

<div class="py-8 flex justify-center">
    <div class="w-full max-w-2xl">

        <a href="/mes-commandes" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-primary transition mb-6">
            <i class="fa-solid fa-arrow-left"></i> Retour à mes commandes
        </a>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></h1>
                    <p class="text-sm text-gray-400 mt-1"><?= date('d/m/Y à H\hi', strtotime($commande->dateCommande)) ?></p>
                </div>
                <span class="text-xs font-bold px-3 py-1.5 rounded-full <?= $couleursStatut[$commande->statut] ?? 'bg-gray-100 text-gray-600' ?>">
                    <?= htmlspecialchars($libelles[$commande->statut] ?? str_replace('_', ' ', $commande->statut)) ?>
                </span>
            </div>

            <?php if ($commande->statut === 'ANNULEE'): ?>
                <div class="bg-red-50 text-red-600 rounded-xl px-4 py-3 text-sm font-semibold mb-6">
                    <i class="fa-solid fa-circle-xmark"></i> Cette commande a été annulée.
                </div>
            <?php else: ?>
                <!-- Suivi du statut -->
                <div class="flex items-center mb-8">
                    <?php foreach ($libelles as $cle => $label): ?>
                        <?php if (in_array($cle, ['EN_ATTENTE', 'EN_PREPARATION', 'PRETE', 'RETIREE'], true)): ?>
                            <div class="flex-1 flex flex-col items-center relative">
                                <div class="absolute top-[9px] left-0 right-0 h-0.5 bg-gray-200 -z-0"></div>
                                <div class="w-5 h-5 rounded-full relative z-10 <?= $etapeCourante >= $steps[$cle] ? 'bg-primary' : 'bg-gray-200' ?> border-2 <?= $etapeCourante >= $steps[$cle] ? 'border-primary' : 'border-gray-300' ?>"></div>
                                <p class="text-[10px] mt-2 text-center leading-tight <?= $etapeCourante >= $steps[$cle] ? 'text-primary font-bold' : 'text-gray-400' ?>">
                                    <?= $label ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Articles -->
            <h2 class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-3">Articles commandés</h2>
            <ul class="divide-y divide-gray-50 mb-6">
                <?php foreach ($commande->lignes as $ligne): ?>
                <li class="py-3 flex items-start justify-between gap-4">
                    <span class="text-sm text-gray-700">
                        <span class="font-semibold"><?= $ligne->quantite ?>x</span>
                        <?= htmlspecialchars((string) $ligne->produitLibelle) ?>
                        <?php if ($ligne->instructionsSpeciales): ?>
                            <span class="block text-xs text-primary italic mt-0.5">Note : <?= htmlspecialchars($ligne->instructionsSpeciales) ?></span>
                        <?php endif; ?>
                    </span>
                    <span class="text-sm font-semibold text-gray-800 shrink-0"><?= number_format($ligne->sousTotal, 0, ' ', ' ') ?> FCFA</span>
                </li>
                <?php endforeach; ?>
            </ul>

            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-500">Total réglé</p>
                <p class="text-2xl font-extrabold text-primary"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</p>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="/commandes/<?= $commande->id ?>/facture"
               class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-primary text-white text-sm font-bold hover:bg-primary-dark transition">
                <i class="fa-regular fa-file-lines"></i> Voir la facture
            </a>
        </div>
    </div>
</div>