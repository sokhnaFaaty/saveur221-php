<?php
/** @var \App\Models\Recu $recu */
/** @var \App\Models\Paiement $paiement */
/** @var \App\Models\Commande $commande */

$moyens = [
    'WAVE'         => 'Wave',
    'ORANGE_MONEY' => 'Orange Money',
    'ESPECES'      => 'Espèces',
];
$libelleMoyen = $moyens[$paiement->moyen] ?? $paiement->moyen;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu <?= htmlspecialchars($recu->numero) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "DejaVu Sans", sans-serif; color: #1f2937; margin: 0; padding: 0; font-size: 13px; }
        .page { width: 100%; }
        .entete { background-color: #166534; color: #ffffff; padding: 24px 32px; margin: 0; }
        .entete h1 { margin: 0 0 4px 0; font-size: 21px; }
        .entete p { margin: 2px 0; color: #d1fae5; font-size: 12px; }
        .marque { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .rouge { color: #fca5a5; }
        .droite { text-align: right; }
        .corps { padding: 24px 32px; }
        table { width: 100%; border-collapse: collapse; }
        .grille { margin-bottom: 20px; }
        .grille td { width: 50%; background-color: #f9fafb; border: 1px solid #f3f4f6; padding: 10px 14px; vertical-align: top; }
        .grille .lib { display: block; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; margin-bottom: 3px; }
        .grille .val { font-weight: bold; color: #1f2937; }
        thead th { text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; border-bottom: 1px solid #f3f4f6; padding: 0 8px 9px 8px; }
        thead th.centre { text-align: center; }
        thead th.droite { text-align: right; }
        tbody td { padding: 11px 8px; border-bottom: 1px solid #f9fafb; }
        tbody td.centre { text-align: center; color: #6b7280; }
        tbody td.droite { text-align: right; font-weight: bold; }
        .poids { font-weight: bold; }
        .total-ligne { border-top: 2px dashed #e5e7eb; padding: 12px 8px 6px 8px; }
        .total-ligne td { border: none; padding: 4px 8px; }
        .label { text-transform: uppercase; letter-spacing: 1px; font-size: 11px; color: #9ca3af; font-weight: bold; padding-right: 12px; }
        .total { font-size: 18px; font-weight: bold; text-align: right; }
        .regle { font-size: 26px; font-weight: bold; color: #16a34a; text-align: right; }
        .pied { text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #f3f4f6; padding: 14px 0 6px 0; margin-top: 26px; }
    </style>
</head>
<body>
<div class="page">
    <div class="entete">
        <div class="marque">Saveur <span class="rouge">221</span></div>
        <p>[Paiement encaissé]</p>
        <h1>Reçu <?= htmlspecialchars($recu->numero) ?></h1>
        <p>Commande <?= htmlspecialchars($commande->numCommande) ?></p>
        <p class="droite">Date d'émission : <?= date('d/m/Y à H\hi', strtotime($recu->dateEmission)) ?></p>
    </div>

    <div class="corps">
        <table class="grille">
            <tr>
                <td>
                    <span class="lib">Mode de paiement</span>
                    <span class="val"><?= htmlspecialchars($libelleMoyen) ?></span>
                </td>
                <td>
                    <span class="lib">Date du paiement</span>
                    <span class="val"><?= date('d/m/Y à H\hi', strtotime($paiement->datePaiement)) ?></span>
                </td>
            </tr>
        </table>

        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="centre">Qté</th>
                    <th class="droite">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commande->lignes as $ligne): ?>
                <tr>
                    <td class="poids"><?= htmlspecialchars((string) $ligne->produitLibelle) ?></td>
                    <td class="centre"><?= $ligne->quantite ?></td>
                    <td class="droite"><?= number_format($ligne->sousTotal, 0, ' ', ' ') ?> FCFA</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="total-ligne">
            <tr>
                <td class="label">Total facturé</td>
                <td class="total"><?= number_format($commande->total, 0, ' ', ' ') ?> FCFA</td>
            </tr>
            <tr>
                <td class="label">Montant réglé</td>
                <td class="regle"><?= number_format($paiement->montant, 0, ' ', ' ') ?> FCFA</td>
            </tr>
        </table>

        <div class="pied">
            Route des Almadies, Dakar, Sénégal — +221 78 540 55 93 — Merci de votre confiance !
        </div>
    </div>
</div>
</body>
</html>