<?php
/** @var \App\Models\Commande[] $commandes */
/** @var array<int, \App\Models\Paiement[]> $paiementsParCommande */
/** @var array<int, \App\Models\Avis|null> $avisParCommande */
$user = $_SESSION['user'] ?? null;
$paiementsParCommande = $paiementsParCommande ?? [];
$avisParCommande = $avisParCommande ?? [];
$mesAvis = $mesAvis ?? [];

$statuts = ['EN_ATTENTE', 'EN_PREPARATION', 'PRETE', 'RETIREE', 'ANNULEE'];
$statutFiltre = (string) ($_GET['statut'] ?? '');
$recherche = trim((string) ($_GET['recherche'] ?? ''));
$commandesFiltrees = $commandes;

if ($statutFiltre !== '' && in_array($statutFiltre, $statuts, true)) {
    $commandesFiltrees = array_values(array_filter($commandesFiltrees, fn ($c) => $c->statut === $statutFiltre));
}
if ($recherche !== '') {
    $filtre = strtolower($recherche);
    $dateCherchee = preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $recherche, $m)
        ? "{$m[3]}-{$m[2]}-{$m[1]}" : null;
    $commandesFiltrees = array_values(array_filter(
        $commandesFiltrees,
        fn ($c) => str_contains(strtolower($c->numCommande), $filtre)
            || (str_contains(strtolower(date('d/m/Y', strtotime($c->dateCommande))), $filtre))
            || ($dateCherchee !== null && str_starts_with($c->dateCommande, $dateCherchee))
    ));
}

$couleursStatut = [
    'EN_ATTENTE'     => 'bg-amber-100 text-amber-800',
    'EN_PREPARATION' => 'bg-blue-100 text-blue-800',
    'PRETE'          => 'bg-green-100 text-green-800',
    'RETIREE'        => 'bg-gray-900 text-white',
    'ANNULEE'        => 'bg-red-100 text-red-700',
];
$libellesStatut = [
    'EN_ATTENTE'     => 'En attente',
    'EN_PREPARATION' => 'En préparation',
    'PRETE'          => 'Prête',
    'RETIREE'        => 'Retirée',
    'ANNULEE'        => 'Annulée',
];

$totalCommandes = $totalCommandes ?? count($commandesFiltrees);
$totalAvis = $totalAvis ?? 0;

$avatar = !empty($user['image']) ? htmlspecialchars((string) $user['image']) : null;
$nomComplet = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));

$onglet = $onglet ?? 'commandes';
$vue = $vue ?? 'cartes';
?>

<div class="-mx-6 bg-gray-100 min-h-screen px-6 py-8">

    <!-- ===== 1. EN-TÊTE PROFIL ===== -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                <?php if ($avatar): ?>
                    <img src="<?= $avatar ?>" alt="Photo de profil" class="w-16 h-16 rounded-full object-cover border-2 border-primary-light">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-primary-light text-primary flex items-center justify-center text-2xl font-extrabold">
                        <?= strtoupper(mb_substr((string) ($user['prenom'] ?? 'U'), 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-900"><?= htmlspecialchars($nomComplet !== '' ? $nomComplet : 'Mon espace client') ?></h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
            <a href="/catalogue" class="px-5 py-3 rounded-lg bg-[#A8291A] text-white text-sm font-bold hover:bg-[#8A2013] transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-utensils"></i> Commander un plat
            </a>
            <a href="/deconnexion" class="px-5 py-3 rounded-lg bg-white border border-gray-300 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </div>

    <!-- ===== 2. BARRE D'ONGLETS ===== -->
    <div class="flex flex-wrap items-center gap-1 border-b border-gray-200 mb-6">
        <?php
        $onglets = [
            'commandes' => ['label' => 'Mes commandes', 'count' => $totalCommandes],
            'factures'  => ['label' => 'Factures & Reçus', 'count' => $totalCommandes],
            'avis'      => ['label' => 'Mes Avis', 'count' => $totalAvis],
            'profil'    => ['label' => 'Profil', 'count' => null],
        ];
        foreach ($onglets as $cle => $info):
        ?>
        <a href="?onglet=<?= $cle ?>"
           class="onglet px-4 py-3 text-sm font-semibold border-b-2 transition <?= $onglet === $cle ? 'border-[#A8291A] text-[#A8291A] font-bold' : 'border-transparent text-gray-400 hover:text-gray-600' ?>">
            <?= $info['label'] ?><?= $info['count'] !== null ? " ({$info['count']})" : '' ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- ===== 3.1 MES COMMANDES ===== -->
    <?php if ($onglet === 'commandes'): ?>
    <section id="vue-commandes">
        <!-- Toggle cartes/tableau + filtres -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6">
            <form method="get" class="w-full lg:w-80">
                <input type="hidden" name="onglet" value="commandes">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" name="recherche" value="<?= htmlspecialchars($recherche) ?>" placeholder="N° commande, date..."
                           class="w-full pl-10 pr-24 py-2.5 rounded-lg bg-white border border-gray-300 text-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary">
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-3.5 rounded-md bg-[#A8291A] text-white text-xs font-bold hover:bg-[#8A2013]">OK</button>
                </div>
            </form>
            <div class="flex items-center gap-3 flex-wrap">
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <a href="?onglet=commandes&vue=cartes" class="px-3 py-1.5 rounded-md text-xs font-bold transition <?= $vue === 'cartes' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' ?>"><i class="fa-solid fa-grip"></i> Cartes</a>
                    <a href="?onglet=commandes&vue=tableau" class="px-3 py-1.5 rounded-md text-xs font-bold transition <?= $vue === 'tableau' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' ?>"><i class="fa-solid fa-table-list"></i> Tableau</a>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    <a href="?onglet=commandes" class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $statutFiltre === '' ? 'bg-[#A8291A] text-white' : 'bg-white border border-gray-300 text-gray-600' ?>">Tous</a>
                    <?php foreach ($statuts as $s): ?>
                    <a href="?onglet=commandes&statut=<?= $s ?>" class="px-3 py-1.5 rounded-lg text-xs font-semibold <?= $statutFiltre === $s ? 'bg-[#A8291A] text-white' : 'bg-white border border-gray-300 text-gray-600' ?>"><?= $libellesStatut[$s] ?? $s ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if ($commandesFiltrees === []): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-300"><i class="fa-solid fa-receipt"></i></div>
                <p class="font-bold text-gray-700">Aucune commande</p>
                <p class="text-sm text-gray-400 mt-1">Commandez votre premier plat !</p>
                <a href="/catalogue" class="inline-block mt-6 px-6 py-3 rounded-lg bg-[#A8291A] text-white text-sm font-bold">Voir le catalogue</a>
            </div>
        <?php endif; ?>

        <?php if ($vue === 'cartes'): ?>
            <?php foreach ($commandesFiltrees as $commande): ?>
            <?php $dejaEvalue = isset($avisParCommande[$commande->id]) && $avisParCommande[$commande->id] !== null; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></h3>
                        <p class="text-xs text-gray-400 mt-0.5"><?= date('d/m/Y à H\hi', strtotime($commande->dateCommande)) ?></p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full <?= $couleursStatut[$commande->statut] ?? 'bg-gray-100 text-gray-600' ?>"><?= $libellesStatut[$commande->statut] ?? $commande->statut ?></span>
                </div>
                <div class="grid md:grid-cols-[1fr_auto] gap-6 items-center">
                    <div>
                        <ul class="space-y-1 mb-3">
                            <?php foreach ($commande->lignes as $ligne): ?>
                            <li class="text-sm text-gray-700">
                                <span class="font-semibold"><?= $ligne->quantite ?>x</span> <?= htmlspecialchars((string) $ligne->produitLibelle) ?>
                                <span class="text-gray-400 text-xs">— <?= number_format($ligne->sousTotal, 0) ?> FCFA</span>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-2xl font-extrabold text-[#A8291A]"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</p>
                        <div class="flex items-center justify-end gap-2 mt-3 flex-wrap">
                            <a href="/commandes/<?= $commande->id ?>/facture" class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-50"><i class="fa-regular fa-file-lines"></i> Facture</a>
                            <?php $paiementsCmd = $paiementsParCommande[$commande->id] ?? []; $dernier = $paiementsCmd !== [] ? end($paiementsCmd) : null; ?>
                            <?php if ($dernier): ?>
                                <a href="/recus/<?= $dernier->id ?>" class="px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs font-semibold"><i class="fa-solid fa-circle-check"></i> Reçu</a>
                            <?php endif; ?>
                            <a href="/commandes/<?= $commande->id ?>" class="px-3 py-1.5 rounded-lg border-2 border-[#A8291A] text-[#A8291A] text-xs font-bold hover:bg-[#A8291A] hover:text-white"><i class="fa-solid fa-route"></i> Suivre</a>
                        </div>
                    </div>
                </div>
                <?php if ($commande->statut === 'RETIREE' && !$dejaEvalue): ?>
                <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end">
                    <button type="button" data-commande-id="<?= $commande->id ?>" data-num-commande="<?= htmlspecialchars($commande->numCommande) ?>" onclick="ouvrirModalAvis(this)" class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold"><i class="fa-solid fa-star"></i> Laisser un avis</button>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>

            <?php if (($totalPagesCommandes ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPagesCommandes; $i++): ?>
                <a href="?onglet=commandes&page_commandes=<?= $i ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold <?= $i === ($pageCommandes ?? 1) ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 hover:border-primary' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>

        <?php else: ?>
        <!-- VUE TABLEAU -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">N° Commande</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Statut</th>
                        <th class="px-6 py-4 font-semibold">Articles</th>
                        <th class="px-6 py-4 font-semibold text-right">Montant</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($commandesFiltrees as $commande): ?>
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                        <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y H:i', strtotime($commande->dateCommande)) ?></td>
                        <td class="px-6 py-4"><span class="text-xs font-bold px-3 py-1 rounded-full <?= $couleursStatut[$commande->statut] ?? '' ?>"><?= $libellesStatut[$commande->statut] ?? $commande->statut ?></span></td>
                        <td class="px-6 py-4 text-gray-600"><?= count($commande->lignes) ?> article(s)</td>
                        <td class="px-6 py-4 text-right font-extrabold text-[#A8291A]"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                <a href="/commandes/<?= $commande->id ?>/facture" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs font-semibold hover:bg-gray-50"><i class="fa-regular fa-file-lines"></i> Facture</a>
                                <?php foreach ($paiementsParCommande[$commande->id] ?? [] as $paiement): ?>
                                <a href="/recus/<?= $paiement->id ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs font-semibold"><i class="fa-solid fa-circle-check"></i> Reçu</a>
                                <?php endforeach; ?>
                                <a href="/commandes/<?= $commande->id ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border-2 border-[#A8291A] text-[#A8291A] text-xs font-bold hover:bg-[#A8291A] hover:text-white"><i class="fa-solid fa-route"></i> Suivre</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($commandesFiltrees === []): ?>
                    <tr><td colspan="6" class="text-center text-gray-400 py-12">Aucune commande.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

            <?php if (($totalPagesCommandes ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPagesCommandes; $i++): ?>
                <a href="?onglet=commandes&vue=tableau&page_commandes=<?= $i ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold <?= $i === ($pageCommandes ?? 1) ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 hover:border-primary' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- ===== 3.2 FACTURES & REÇUS ===== -->
    <?php if ($onglet === 'factures'): ?>
    <section id="vue-factures">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-extrabold text-gray-900">Factures & Reçus</h2>
            <div class="flex bg-gray-100 rounded-lg p-1">
                <a href="?onglet=factures&vue=cartes" class="px-3 py-1.5 rounded-md text-xs font-bold transition <?= $vue === 'cartes' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' ?>"><i class="fa-solid fa-grip"></i> Cartes</a>
                <a href="?onglet=factures&vue=tableau" class="px-3 py-1.5 rounded-md text-xs font-bold transition <?= $vue === 'tableau' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' ?>"><i class="fa-solid fa-table-list"></i> Tableau</a>
            </div>
        </div>

        <?php
        $commandesFactures = $commandesFactures ?? [];
        ?>

        <?php if ($commandesFactures === []): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-300"><i class="fa-solid fa-file-invoice"></i></div>
                <p class="font-bold text-gray-700">Aucune facture pour le moment</p>
                <p class="text-sm text-gray-400 mt-1">Vos factures apparaîtront après vos commandes.</p>
            </div>
        <?php else: ?>

        <?php if ($vue === 'cartes'): ?>
            <?php foreach ($commandesFactures as $commande): ?>
            <?php $paiementsCmd = $paiementsParCommande[$commande->id] ?? []; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                <div class="flex flex-wrap items-start justify-between gap-3 mb-4">
                    <div>
                        <h3 class="font-extrabold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></h3>
                        <p class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($commande->dateCommande)) ?></p>
                    </div>
                    <span class="text-xs font-bold px-3 py-1.5 rounded-full <?= $couleursStatut[$commande->statut] ?? '' ?>"><?= $libellesStatut[$commande->statut] ?? $commande->statut ?></span>
                </div>
                <div class="flex items-center justify-between">
                    <p class="text-2xl font-extrabold text-[#A8291A]"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="/commandes/<?= $commande->id ?>/facture" class="px-4 py-2 rounded-lg bg-white border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50"><i class="fa-regular fa-file-lines"></i> Facture</a>
                        <?php foreach ($paiementsCmd as $paiement): ?>
                            <a href="/recus/<?= $paiement->id ?>" class="px-4 py-2 rounded-lg bg-green-50 border border-green-200 text-green-700 text-sm font-semibold hover:bg-green-100"><i class="fa-solid fa-circle-check"></i> Reçu</a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-4 font-semibold">N° Commande</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold text-right">Montant</th>
                            <th class="px-6 py-4 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($commandesFactures as $commande): ?>
                        <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></td>
                            <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y H:i', strtotime($commande->dateCommande)) ?></td>
                            <td class="px-6 py-4 text-right font-extrabold text-[#A8291A]"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="/commandes/<?= $commande->id ?>/facture" class="px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-xs font-semibold hover:bg-gray-50"><i class="fa-regular fa-file-lines"></i> Facture</a>
                                    <?php foreach ($paiementsParCommande[$commande->id] ?? [] as $paiement): ?>
                                    <a href="/recus/<?= $paiement->id ?>" class="px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs font-semibold"><i class="fa-solid fa-circle-check"></i> Reçu</a>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

            <?php if (($totalPagesFactures ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPagesFactures; $i++): ?>
                <a href="?onglet=factures&vue=<?= $vue ?>&page_factures=<?= $i ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold <?= $i === ($pageFactures ?? 1) ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 hover:border-primary' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- ===== 3.3 MES AVIS ===== -->
    <?php if ($onglet === 'avis'): ?>
    <section id="vue-avis">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-extrabold text-gray-900">Mes Avis & Retours</h2>
            <div class="flex bg-gray-100 rounded-lg p-1">
                <a href="?onglet=avis&vue=cartes" class="px-3 py-1.5 rounded-md text-xs font-bold transition <?= $vue === 'cartes' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' ?>"><i class="fa-solid fa-grip"></i> Cartes</a>
                <a href="?onglet=avis&vue=tableau" class="px-3 py-1.5 rounded-md text-xs font-bold transition <?= $vue === 'tableau' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-500' ?>"><i class="fa-solid fa-table-list"></i> Tableau</a>
            </div>
        </div>

        <?php if ($mesAvis === []): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-300"><i class="fa-solid fa-star"></i></div>
                <p class="font-bold text-gray-700">Aucun avis pour le moment</p>
                <p class="text-sm text-gray-400 mt-1">Après retrait, vous pourrez partager votre expérience.</p>
            </div>
        <?php else: ?>

        <?php if ($vue === 'cartes'): ?>
            <?php foreach ($mesAvis as $entry): ?>
            <?php $avis = $entry['avis']; ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
                <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center"><i class="fa-solid fa-star"></i></span>
                        <div>
                            <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($entry['numCommande']) ?></p>
                            <p class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($avis->dateAvis)) ?></p>
                        </div>
                    </div>
                    <div class="text-amber-500 text-sm"><?= str_repeat('★', $avis->note) . str_repeat('☆', 5 - $avis->note) ?></div>
                </div>
                <?php if ($avis->commentaire): ?>
                    <p class="text-sm text-gray-600 bg-gray-50 rounded-lg px-4 py-3">« <?= htmlspecialchars($avis->commentaire) ?> »</p>
                <?php else: ?>
                    <p class="text-sm text-gray-400 italic">Aucun commentaire.</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                            <th class="px-6 py-4 font-semibold">Commande</th>
                            <th class="px-6 py-4 font-semibold">Date</th>
                            <th class="px-6 py-4 font-semibold">Note</th>
                            <th class="px-6 py-4 font-semibold">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($mesAvis as $entry): ?>
                        <?php $avis = $entry['avis']; ?>
                        <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($entry['numCommande']) ?></td>
                            <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y', strtotime($avis->dateAvis)) ?></td>
                            <td class="px-6 py-4 text-amber-500"><?= str_repeat('★', $avis->note) . str_repeat('☆', 5 - $avis->note) ?></td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs truncate"><?= htmlspecialchars((string) $avis->commentaire) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

            <?php if (($totalPagesAvis ?? 1) > 1): ?>
            <div class="flex items-center justify-center gap-2 mt-6">
                <?php for ($i = 1; $i <= $totalPagesAvis; $i++): ?>
                <a href="?onglet=avis&vue=<?= $vue ?>&page_avis=<?= $i ?>" class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold <?= $i === ($pageAvis ?? 1) ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 hover:border-primary' ?>"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
    <?php endif; ?>

    <!-- ===== 3.4 PROFIL ===== -->
    <?php if ($onglet === 'profil'): ?>
    <section id="vue-profil">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-2xl">
            <div class="flex items-center gap-4 mb-6">
                <?php if ($avatar): ?>
                    <img src="<?= $avatar ?>" class="w-16 h-16 rounded-full object-cover border-2 border-primary-light">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-primary-light text-primary flex items-center justify-center text-2xl font-extrabold"><?= strtoupper(mb_substr((string) ($user['prenom'] ?? 'U'), 0, 1)) ?></div>
                <?php endif; ?>
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900"><?= htmlspecialchars($nomComplet) ?></h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></p>
                </div>
            </div>
            <dl class="divide-y divide-gray-100 text-sm mb-6">
                <div class="flex items-center justify-between py-3"><dt class="text-gray-500">Nom complet</dt><dd class="font-semibold text-gray-900"><?= htmlspecialchars($nomComplet) ?></dd></div>
                <div class="flex items-center justify-between py-3"><dt class="text-gray-500">E-mail</dt><dd class="font-semibold text-gray-900"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></dd></div>
                <div class="flex items-center justify-between py-3"><dt class="text-gray-500">Rôle</dt><dd class="font-semibold text-gray-900">Client</dd></div>
            </dl>
            <div class="flex flex-wrap gap-3">
                <a href="/profil" class="px-5 py-3 rounded-lg bg-[#A8291A] text-white text-sm font-bold hover:bg-[#8A2013] transition flex items-center gap-2"><i class="fa-solid fa-user-gear"></i> Gérer mon profil</a>
                <a href="/profil" class="px-5 py-3 rounded-lg bg-white border border-gray-300 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center gap-2"><i class="fa-solid fa-lock"></i> Mot de passe</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

</div>

<!-- ===== MODALE AVIS ===== -->
<div id="modal-avis" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-bold text-lg">Laisser un avis</h3>
                <p class="text-xs text-gray-400">Commande <span id="modal-avis-commande" class="font-semibold"></span></p>
            </div>
            <button type="button" onclick="fermerModalAvis()" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-avis" method="post">
            <input type="hidden" name="note" id="avis-note" value="0">
            <div id="etoiles-avis" class="flex items-center justify-center gap-1.5 mb-5 text-3xl">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" class="etoile text-gray-300 hover:text-amber-500 hover:scale-110 transition" data-valeur="<?= $i ?>"><i class="fa-solid fa-star"></i></button>
                <?php endfor; ?>
            </div>
            <textarea name="commentaire" rows="4" class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none resize-none" placeholder="Partagez votre expérience... (facultatif)"></textarea>
            <div class="flex items-center gap-3 mt-5">
                <button type="button" onclick="fermerModalAvis()" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold hover:bg-gray-50">Annuler</button>
                <button type="submit" class="flex-1 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold">Envoyer</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    var modalAvis = document.getElementById('modal-avis');
    var formAvis = document.getElementById('form-avis');
    var champNote = document.getElementById('avis-note');
    var spanCommande = document.getElementById('modal-avis-commande');

    window.ouvrirModalAvis = function (btn) {
        formAvis.action = '/commandes/' + btn.dataset.commandeId + '/avis';
        spanCommande.textContent = btn.dataset.numCommande;
        champNote.value = 0;
        document.querySelectorAll('#etoiles-avis .etoile').forEach(function(e){ e.classList.add('text-gray-300'); e.classList.remove('text-amber-500'); });
        modalAvis.classList.remove('hidden');
    };
    window.fermerModalAvis = function () { modalAvis.classList.add('hidden'); };

    document.querySelectorAll('#etoiles-avis .etoile').forEach(function (etoile) {
        etoile.addEventListener('click', function () {
            champNote.value = etoile.dataset.valeur;
            document.querySelectorAll('#etoiles-avis .etoile').forEach(function (e) {
                e.classList.toggle('text-amber-500', parseInt(e.dataset.valeur) <= parseInt(champNote.value));
                e.classList.toggle('text-gray-300', parseInt(e.dataset.valeur) > parseInt(champNote.value));
            });
        });
    });

    modalAvis.addEventListener('click', function (e) { if (e.target === modalAvis) fermerModalAvis(); });
    modalAvis.addEventListener('submit', function (e) { if (champNote.value === '0') { e.preventDefault(); alert('Choisissez une note.'); } });
})();
</script>
