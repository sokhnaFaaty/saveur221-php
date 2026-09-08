<?php
/** @var \App\Models\Commande[] $commandes */
/** @var array<int, \App\Models\Paiement[]> $paiementsParCommande */
/** @var array<int, \App\Models\Avis|null> $avisParCommande */
$user = $_SESSION['user'] ?? null;
$paiementsParCommande = $paiementsParCommande ?? [];
$avisParCommande = $avisParCommande ?? [];

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
    'EN_ATTENTE'     => 'En attente de confirmation',
    'EN_PREPARATION' => 'En préparation',
    'PRETE'          => 'Prête au comptoir',
    'RETIREE'        => 'Commande retirée',
    'ANNULEE'        => 'Commande annulée',
];

$libellesPaiement = [
    'IMPAYEE'             => 'Impayée',
    'PARTIELLEMENT_PAYEE' => 'Partiellement payée',
    'PAYEE'               => 'Payée',
];
$couleursPaiement = [
    'IMPAYEE'             => 'bg-red-50 text-red-600',
    'PARTIELLEMENT_PAYEE' => 'bg-amber-50 text-amber-600',
    'PAYEE'               => 'bg-green-50 text-green-700',
];

$nbCommandes = count($commandesFiltrees);
$nbAvis = count(array_filter($avisParCommande, static fn ($a) => $a !== null));

$avatar = !empty($user['image']) ? htmlspecialchars((string) $user['image']) : null;
$nomComplet = trim(($user['prenom'] ?? '') . ' ' . ($user['nom'] ?? ''));
?>

<div class="-mx-6 bg-gray-100 min-h-screen px-6 py-8">

    <!-- ===== 1. EN-TÊTE PROFIL ===== -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                <?php if ($avatar): ?>
                    <img src="<?= $avatar ?>" alt="Photo de profil"
                         class="w-16 h-16 rounded-full object-cover border-2 border-primary-light">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-primary-light text-primary flex items-center justify-center text-2xl font-extrabold">
                        <?= strtoupper(mb_substr((string) ($user['prenom'] ?? 'U'), 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h1 class="text-xl md:text-2xl font-extrabold text-gray-900"><?= htmlspecialchars($nomComplet !== '' ? $nomComplet : 'Mon espace client') ?></h1>
                <p class="text-sm text-gray-500 mt-0.5"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></p>
                <p class="text-sm text-gray-400">Client fidèle Saveur 221</p>
            </div>
        </div>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0">
            <a href="/catalogue"
               class="px-5 py-3 rounded-lg bg-[#A8291A] text-white text-sm font-bold hover:bg-[#8A2013] transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-utensils"></i> Commander un plat
            </a>
            <a href="/deconnexion"
               class="px-5 py-3 rounded-lg bg-white border border-gray-300 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> Déconnexion
            </a>
        </div>
    </div>

    <!-- ===== 2. BARRE DE NAVIGATION / ONGLETS ===== -->
    <div class="flex flex-wrap items-center gap-1 border-b border-gray-200 mb-6">
        <button type="button" data-onglet="cartes"
                class="onglet px-4 py-3 text-sm font-bold border-b-2 border-[#A8291A] text-[#A8291A] transition">
            Mes commandes (<?= $nbCommandes ?>)
        </button>
        <button type="button" data-onglet="tableau"
                class="onglet px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition">
            Factures &amp; Reçus (<?= $nbCommandes ?>)
        </button>
        <button type="button" data-onglet="avis"
                class="onglet px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition">
            Mes Avis &amp; Retours (<?= $nbAvis ?>)
        </button>
        <button type="button" data-onglet="profil"
                class="onglet px-4 py-3 text-sm font-semibold border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition">
            Profil &amp; Sécurité
        </button>
    </div>

    <!-- ===== 3.1 AFFICHAGE PAR CARTES (Mes commandes) ===== -->
    <section id="vue-cartes">

        <!-- Recherche + Filtres de statuts -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3 mb-6">
            <form method="get" action="/mes-commandes" class="w-full lg:w-80">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </span>
                    <input type="text" name="recherche" value="<?= htmlspecialchars($recherche) ?>"
                           placeholder="Rechercher (n° commande, date jj/mm/aaaa)..."
                           class="w-full pl-10 pr-24 py-2.5 rounded-lg bg-white border border-gray-300 text-sm placeholder-gray-400
                                  focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    <button type="submit" class="absolute inset-y-1.5 right-1.5 px-3.5 rounded-md bg-[#A8291A] text-white text-xs font-bold hover:bg-[#8A2013] transition">Rechercher</button>
                </div>
            </form>
            <div class="flex flex-wrap gap-2">
                <a href="/mes-commandes<?= $recherche !== '' ? '?recherche=' . urlencode($recherche) : '' ?>"
                   class="px-4 py-2 rounded-lg text-sm font-semibold <?= $statutFiltre === '' ? 'bg-[#A8291A] text-white shadow-sm' : 'bg-white border border-gray-300 text-gray-600 hover:border-[#A8291A] hover:text-[#A8291A] transition' ?>">Tous</a>
                <?php foreach ($statuts as $s): ?>
                <a href="/mes-commandes?statut=<?= $s ?><?= $recherche !== '' ? '&recherche=' . urlencode($recherche) : '' ?>"
                   class="px-4 py-2 rounded-lg text-sm font-semibold <?= $statutFiltre === $s ? 'bg-[#A8291A] text-white shadow-sm' : 'bg-white border border-gray-300 text-gray-600 hover:border-[#A8291A] hover:text-[#A8291A] transition' ?>"><?= htmlspecialchars(str_replace('_', ' ', $s)) ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($commandesFiltrees === []): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-300">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <p class="font-bold text-gray-700">Aucune commande pour le moment</p>
                <p class="text-sm text-gray-400 mt-1"><?= ($statutFiltre !== '' || $recherche !== '') ? 'Aucune commande ne correspond à votre recherche.' : 'Commandez votre premier plat dès maintenant !' ?></p>
                <a href="/catalogue" class="inline-block mt-6 px-6 py-3 rounded-lg bg-[#A8291A] text-white text-sm font-bold hover:bg-[#8A2013] transition">Voir le catalogue</a>
            </div>
        <?php endif; ?>

        <?php foreach ($commandesFiltrees as $commande): ?>
        <?php $dejaEvalue = isset($avisParCommande[$commande->id]) && $avisParCommande[$commande->id] !== null; ?>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-4">
            <!-- Header de la card -->
            <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                <div>
                    <h3 class="text-base md:text-lg font-extrabold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></h3>
                    <p class="text-xs text-gray-400 mt-0.5"><?= date('d/m/Y à H\hi', strtotime($commande->dateCommande)) ?></p>
                </div>
                <span class="text-xs font-bold px-3 py-1.5 rounded-full <?= $couleursStatut[$commande->statut] ?? 'bg-gray-100 text-gray-600' ?>">
                    <?= htmlspecialchars($libellesStatut[$commande->statut] ?? str_replace('_', ' ', $commande->statut)) ?>
                </span>
            </div>

            <!-- Corps de la card -->
            <div class="grid md:grid-cols-[1fr_auto] gap-6 md:gap-10 items-center">
                <div>
                    <ul class="space-y-1.5 mb-3">
                        <?php foreach ($commande->lignes as $ligne): ?>
                        <li class="text-sm text-gray-700">
                            <span class="font-semibold"><?= $ligne->quantite ?>x</span>
                            <?= htmlspecialchars((string) $ligne->produitLibelle) ?>
                            <span class="text-gray-400 text-xs">— <?= number_format($ligne->sousTotal, 0) ?> FCFA</span>
                            <?php if ($ligne->instructionsSpeciales): ?>
                                <span class="block text-xs text-primary italic mt-0.5">Note : <?= htmlspecialchars($ligne->instructionsSpeciales) ?></span>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <p class="text-sm text-gray-500 flex items-center gap-2">
                        <span class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-primary shrink-0"><i class="fa-solid fa-clock"></i></span>
                        Retrait programmé : <span class="font-semibold text-gray-700"><?= date('H:i', strtotime($commande->dateCommande)) ?></span>
                    </p>
                </div>

                <div class="text-right shrink-0 max-w-[280px]">
                    <?php $paiementsCommande = $paiementsParCommande[$commande->id] ?? []; ?>
                    <?php $sommePayee = array_sum(array_map(fn ($p) => $p->montant, $paiementsCommande)); ?>
                    <?php $reste = $commande->total - $sommePayee; ?>
                    <?php $statutPaiement = $sommePayee <= 0 ? 'IMPAYEE' : ($sommePayee < $commande->total ? 'PARTIELLEMENT_PAYEE' : 'PAYEE'); ?>

                    <span class="text-xs font-bold px-3 py-1 rounded-full <?= $couleursPaiement[$statutPaiement] ?> inline-block mb-2">
                        <?= $libellesPaiement[$statutPaiement] ?>
                    </span>
                    <p class="text-xs uppercase tracking-wide text-gray-400 font-semibold mb-1">Total commande</p>
                    <p class="text-3xl font-extrabold text-[#A8291A]"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</p>

                    <?php $libellesMoyens = ['WAVE' => 'Wave', 'ORANGE_MONEY' => 'Orange Money', 'ESPECES' => 'Espèces']; ?>
                    <?php if ($paiementsCommande !== []): ?>
                        <div class="mt-3 text-left bg-gray-50 rounded-xl p-3 space-y-1.5">
                            <p class="text-[11px] uppercase tracking-wide text-gray-400 font-semibold">Paiements</p>
                            <?php foreach ($paiementsCommande as $p): ?>
                                <div class="flex items-center justify-between gap-3 text-xs">
                                    <span>
                                        <span class="font-bold text-green-700"><?= number_format($p->montant, 0, ' ', ' ') ?> FCFA</span>
                                        <span class="text-gray-500"> · <?= $libellesMoyens[$p->moyen] ?? $p->moyen ?> · <?= date('d/m/Y', strtotime($p->datePaiement)) ?></span>
                                    </span>
                                    <a href="/recus/<?= $p->id ?>" class="text-green-700 hover:underline font-semibold" title="Voir le reçu">
                                        <i class="fa-solid fa-receipt"></i> Reçu
                                    </a>
                                </div>
                            <?php endforeach; ?>
                            <?php if ($reste > 0): ?>
                                <div class="flex items-center justify-between border-t border-gray-200 pt-1.5 text-xs">
                                    <span class="font-semibold text-gray-500">Reste à payer</span>
                                    <span class="font-extrabold text-red-600"><?= number_format($reste, 0, ' ', ' ') ?> FCFA</span>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center justify-between border-t border-gray-200 pt-1.5 text-xs">
                                    <span class="font-semibold text-gray-500">Reste à payer</span>
                                    <span class="font-extrabold text-green-700">Règlement complet</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php elseif ($commande->total > 0): ?>
                        <p class="mt-3 text-xs text-red-500 font-semibold bg-red-50 rounded-xl px-3 py-2 text-right">Aucun paiement — commande impayée.</p>
                    <?php endif; ?>

                    <div class="flex items-center justify-end gap-2 mt-4">
                        <?php $dernierPaiement = $paiementsCommande === [] ? null : $paiementsCommande[count($paiementsCommande) - 1]; ?>
                        <a href="/commandes/<?= $commande->id ?>/facture"
                           class="px-4 py-2 rounded-lg bg-white text-gray-700 border border-gray-300 text-sm font-semibold hover:bg-gray-50 transition flex items-center gap-2">
                            <i class="fa-regular fa-file-lines"></i> Facture
                        </a>
                        <?php if ($dernierPaiement): ?>
                            <a href="/recus/<?= $dernierPaiement->id ?>"
                               class="px-4 py-2 rounded-lg bg-white text-green-700 border border-green-300 text-sm font-semibold hover:bg-green-50 transition flex items-center gap-2">
                                <i class="fa-solid fa-circle-check"></i> Reçu
                            </a>
                        <?php endif; ?>
                        <a href="/commandes/<?= $commande->id ?>"
                           class="px-4 py-2 rounded-lg border-2 border-[#A8291A] text-[#A8291A] text-sm font-bold hover:bg-[#A8291A] hover:text-white transition flex items-center gap-2">
                            <i class="fa-solid fa-route"></i> Suivre
                        </a>
                    </div>
                </div>
            </div>

            <!-- Zone avis -->
            <div class="mt-5 pt-4 border-t border-gray-100 flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500 flex items-center gap-2">
                    <i class="fa-solid fa-comment-dots text-primary"></i> Votre avis compte !
                </p>
                <?php if ($commande->statut === 'RETIREE'): ?>
                    <?php if ($dejaEvalue): ?>
                        <span class="text-xs font-semibold px-3 py-2 rounded-lg bg-gray-100 text-gray-500 flex items-center gap-2">
                            <i class="fa-solid fa-circle-check"></i> Avis déjà soumis
                        </span>
                    <?php else: ?>
                        <button type="button" data-commande-id="<?= $commande->id ?>" data-num-commande="<?= htmlspecialchars($commande->numCommande) ?>"
                                onclick="ouvrirModalAvis(this)"
                                class="px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold transition flex items-center gap-2">
                            <i class="fa-solid fa-star"></i> Laisser un avis
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-xs text-gray-400 flex items-center gap-1.5">
                        <i class="fa-solid fa-circle-info"></i> Avis disponible après retrait
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

    </section>

    <!-- ===== 3.2 AFFICHAGE PAR TABLEAU (Factures & Reçus) ===== -->
    <section id="vue-tableau" class="hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-wide text-gray-400 border-b border-gray-100">
                        <th class="px-6 py-4 font-semibold">N° Commande</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Statut</th>
                        <th class="px-6 py-4 font-semibold">Règlement</th>
                        <th class="px-6 py-4 font-semibold text-right">Montant</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($commandesFiltrees as $commande): ?>
                    <?php $paiementsCommande = $paiementsParCommande[$commande->id] ?? []; ?>
                    <?php $sommePayee = array_sum(array_map(fn ($p) => $p->montant, $paiementsCommande)); ?>
                    <?php $statutPaiement = $sommePayee <= 0 ? 'IMPAYEE' : ($sommePayee < $commande->total ? 'PARTIELLEMENT_PAYEE' : 'PAYEE'); ?>
                    <tr class="border-b border-gray-100 last:border-0 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($commande->numCommande) ?></td>
                        <td class="px-6 py-4 text-gray-500"><?= date('d/m/Y H:i', strtotime($commande->dateCommande)) ?></td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-bold px-3 py-1 rounded-full <?= $couleursStatut[$commande->statut] ?? 'bg-gray-100 text-gray-600' ?>">
                                <?= htmlspecialchars($libellesStatut[$commande->statut] ?? str_replace('_', ' ', $commande->statut)) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <?php if ($paiementsCommande !== []): ?>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full <?= $couleursPaiement[$statutPaiement] ?>">
                                    <?= $libellesPaiement[$statutPaiement] ?>
                                </span>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    <?= number_format($sommePayee, 0, ' ', ' ') ?> / <?= number_format($commande->total, 0, ' ', ' ') ?> FCFA
                                </p>
                            <?php else: ?>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full <?= $couleursPaiement['IMPAYEE'] ?>">Impayée</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-right font-extrabold text-[#A8291A]"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 flex-wrap">
                                <a href="/commandes/<?= $commande->id ?>/facture"
                                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-white border border-gray-300 text-gray-700 text-xs font-semibold hover:bg-gray-50 transition">
                                    <i class="fa-regular fa-file-lines"></i> La facture
                                </a>
                                <?php foreach ($paiementsCommande as $paiement): ?>
                                    <a href="/recus/<?= $paiement->id ?>"
                                       class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-green-50 border border-green-200 text-green-700 text-xs font-semibold hover:bg-green-100 transition">
                                        <i class="fa-solid fa-circle-check"></i> Reçu REC-<?= date('Y', strtotime($paiement->datePaiement)) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if ($commandesFiltrees === []): ?>
                    <tr><td colspan="5" class="text-center text-gray-400 py-12">Aucun document à afficher.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ===== 3.3 MES AVIS & RETOURS ===== -->
    <section id="vue-avis" class="hidden">
        <?php $mesAvis = array_filter($avisParCommande, static fn ($a) => $a !== null); ?>
        <?php if ($mesAvis === []): ?>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center text-3xl text-gray-300">
                    <i class="fa-solid fa-star"></i>
                </div>
                <p class="font-bold text-gray-700">Aucun avis pour le moment</p>
                <p class="text-sm text-gray-400 mt-1">Après retrait de votre commande, vous pourrez partager votre expérience.</p>
            </div>
        <?php else: ?>
            <div class="space-y-4">
            <?php foreach ($commandes as $commande): ?>
                <?php if (!$dejaEvalue = (isset($avisParCommande[$commande->id]) && $avisParCommande[$commande->id] !== null)): ?>
                    <?php continue; ?>
                <?php endif; ?>
                <?php $avis = $avisParCommande[$commande->id]; ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-star"></i>
                            </span>
                            <div>
                                <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($commande->numCommande) ?></p>
                                <p class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($avis->dateAvis)) ?></p>
                            </div>
                        </div>
                        <div class="text-amber-500 text-sm"><?= str_repeat('★', $avis->note) . str_repeat('☆', 5 - $avis->note) ?></div>
                    </div>
                    <?php if ($avis->commentaire): ?>
                        <p class="text-sm text-gray-600 bg-gray-50 rounded-lg px-4 py-3">« <?= htmlspecialchars($avis->commentaire) ?> »</p>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 italic">Aucun commentaire laissé.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- ===== 3.4 PROFIL & SECURITE ===== -->
    <section id="vue-profil" class="hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 max-w-2xl">
            <div class="flex items-center gap-4 mb-6">
                <?php if ($avatar): ?>
                    <img src="<?= $avatar ?>" alt="Photo de profil" class="w-16 h-16 rounded-full object-cover border-2 border-primary-light">
                <?php else: ?>
                    <div class="w-16 h-16 rounded-full bg-primary-light text-primary flex items-center justify-center text-2xl font-extrabold">
                        <?= strtoupper(mb_substr((string) ($user['prenom'] ?? 'U'), 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h3 class="text-lg font-extrabold text-gray-900"><?= htmlspecialchars($nomComplet) ?></h3>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></p>
                </div>
            </div>
            <dl class="divide-y divide-gray-100 text-sm mb-6">
                <div class="flex items-center justify-between py-3">
                    <dt class="text-gray-500">Nom complet</dt>
                    <dd class="font-semibold text-gray-900"><?= htmlspecialchars($nomComplet) ?></dd>
                </div>
                <div class="flex items-center justify-between py-3">
                    <dt class="text-gray-500">Adresse e-mail</dt>
                    <dd class="font-semibold text-gray-900"><?= htmlspecialchars((string) ($user['email'] ?? '')) ?></dd>
                </div>
                <div class="flex items-center justify-between py-3">
                    <dt class="text-gray-500">Rôle</dt>
                    <dd class="font-semibold text-gray-900">Client</dd>
                </div>
            </dl>
            <div class="flex flex-wrap gap-3">
                <a href="/profil" class="px-5 py-3 rounded-lg bg-[#A8291A] text-white text-sm font-bold hover:bg-[#8A2013] transition flex items-center gap-2">
                    <i class="fa-solid fa-user-gear"></i> Gérer mon profil
                </a>
                <a href="/profil" class="px-5 py-3 rounded-lg bg-white border border-gray-300 text-gray-600 text-sm font-semibold hover:border-primary hover:text-primary transition flex items-center gap-2">
                    <i class="fa-solid fa-lock"></i> Sécurité &amp; mot de passe
                </a>
            </div>
        </div>
    </section>

</div>

<!-- ===== MODALE AVIS ===== -->
<div id="modal-avis" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md p-6">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h3 class="font-bold text-lg text-gray-900">Laisser un avis</h3>
                <p class="text-xs text-gray-400">Commande <span id="modal-avis-commande" class="font-semibold"></span></p>
            </div>
            <button type="button" onclick="fermerModalAvis()" class="text-gray-400 hover:text-gray-700 transition text-lg"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-avis" method="post">
            <input type="hidden" name="note" id="avis-note" value="0">
            <div id="etoiles-avis" class="flex items-center justify-center gap-1.5 mb-5 text-3xl">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <button type="button" class="etoile text-gray-300 hover:text-amber-500 hover:scale-110 transition" data-valeur="<?= $i ?>">
                        <i class="fa-solid fa-star"></i>
                    </button>
                <?php endfor; ?>
            </div>
            <textarea name="commentaire" rows="4" class="w-full border border-gray-200 rounded-lg p-3 text-sm focus:border-amber-500 focus:ring-1 focus:ring-amber-500 outline-none resize-none"
                      placeholder="Partagez votre expérience... (facultatif)"></textarea>
            <div class="flex items-center gap-3 mt-5">
                <button type="button" onclick="fermerModalAvis()" class="flex-1 py-2.5 rounded-lg border border-gray-300 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition">Annuler</button>
                <button type="submit" class="flex-1 py-2.5 rounded-lg bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold transition">Envoyer mon avis</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        // ===== Onglets =====
        var onglets = document.querySelectorAll('.onglet');
        var vues = {
            cartes: document.getElementById('vue-cartes'),
            tableau: document.getElementById('vue-tableau'),
            avis: document.getElementById('vue-avis'),
            profil: document.getElementById('vue-profil')
        };

        onglets.forEach(function (onglet) {
            onglet.addEventListener('click', function () {
                onglets.forEach(function (o) {
                    o.classList.remove('border-[#A8291A]', 'text-[#A8291A]', 'font-bold');
                    o.classList.add('border-transparent', 'text-gray-400', 'font-semibold');
                });
                onglet.classList.add('border-[#A8291A]', 'text-[#A8291A]', 'font-bold');
                onglet.classList.remove('border-transparent', 'text-gray-400', 'font-semibold');

                Object.keys(vues).forEach(function (cle) {
                    if (vues[cle]) {
                        vues[cle].classList.add('hidden');
                    }
                });
                var cible = vues[onglet.dataset.onglet];
                if (cible) {
                    cible.classList.remove('hidden');
                }
            });
        });

        // ===== Modale avis =====
        var modalAvis = document.getElementById('modal-avis');
        var formAvis = document.getElementById('form-avis');
        var champNote = document.getElementById('avis-note');
        var spanCommande = document.getElementById('modal-avis-commande');

        window.ouvrirModalAvis = function (bouton) {
            formAvis.action = '/commandes/' + bouton.dataset.commandeId + '/avis';
            spanCommande.textContent = bouton.dataset.numCommande;
            champNote.value = 0;
            mettreAJourEtoiles(0);
            modalAvis.classList.remove('hidden');
        };

        window.fermerModalAvis = function () {
            modalAvis.classList.add('hidden');
        };

        window.mettreAJourEtoiles = function (valeur) {
            document.querySelectorAll('#etoiles-avis .etoile').forEach(function (e) {
                if (parseInt(e.dataset.valeur, 10) <= parseInt(valeur, 10)) {
                    e.classList.remove('text-gray-300');
                    e.classList.add('text-amber-500');
                } else {
                    e.classList.remove('text-amber-500');
                    e.classList.add('text-gray-300');
                }
            });
        };

        document.querySelectorAll('#etoiles-avis .etoile').forEach(function (etoile) {
            etoile.addEventListener('click', function () {
                champNote.value = etoile.dataset.valeur;
                mettreAJourEtoiles(etoile.dataset.valeur);
            });
        });

        document.getElementById('modal-avis').addEventListener('click', function (event) {
            if (event.target === modalAvis) {
                fermerModalAvis();
            }
        });

        modalAvis.addEventListener('submit', function (event) {
            if (champNote.value === '0') {
                event.preventDefault();
                alert('Veuillez choisir une note (1 à 5 étoiles).');
            }
        });
    })();
</script>