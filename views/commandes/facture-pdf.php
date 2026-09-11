<?php
/** @var \App\Models\Commande $commande */
/** @var \App\Models\Facture|null $facture */

$statuts = [
    'EN_ATTENTE'      => 'En attente de confirmation',
    'EN_PREPARATION'  => 'En préparation',
    'PRETE'           => 'Prête au comptoir',
    'RETIREE'         => 'Commande retirée',
    'ANNULEE'         => 'Commande annulée',
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture <?= htmlspecialchars((string) ($facture->numero ?? '')) ?></title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: "DejaVu Sans", sans-serif; color: #1f2937; margin: 0; padding: 0; font-size: 13px; }
        .page { width: 100%; }
        .entete { background-color: #111827; color: #ffffff; padding: 24px 32px; margin: 0; }
        .entete h1 { margin: 0 0 4px 0; font-size: 21px; }
        .entete p { margin: 2px 0; color: #9ca3af; font-size: 12px; }
        .marque { font-size: 18px; font-weight: bold; margin-bottom: 10px; }
        .rouge { color: #f87171; }
        .droite { text-align: right; }
        .corps { padding: 24px 32px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af; border-bottom: 1px solid #f3f4f6; padding: 0 8px 9px 8px; }
        thead th.centre { text-align: center; }
        thead th.droite { text-align: right; }
        tbody td { padding: 11px 8px; border-bottom: 1px solid #f9fafb; }
        tbody td.centre { text-align: center; color: #6b7280; }
        tbody td.droite { text-align: right; }
        .poids { font-weight: bold; }
        .unitaire { color: #6b7280; }
        .total-ligne { margin-top: 24px; }
        .total-ligne td { border: none; padding: 4px 8px; }
        .label { text-transform: uppercase; letter-spacing: 1px; font-size: 11px; color: #9ca3af; font-weight: bold; padding-right: 12px; }
        .regle { font-size: 26px; font-weight: bold; color: #a8291a; text-align: right; }
        .pied { text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #f3f4f6; padding: 14px 0 6px 0; margin-top: 26px; }
    </style>
</head>
<body>
<div class="page">
    <div class="entete">
        <div class="marque">Saveur <span class="rouge">221</span></div>
        <h1>Facture <?= htmlspecialchars((string) ($facture->numero ?? '')) ?></h1>
        <p>Commande <?= htmlspecialchars($commande->numCommande) ?></p>
        <p class="droite">Date d'émission : <?= htmlspecialchars((string) ($facture->dateEmission ?? $commande->dateCommande)) ?></p>
        <p class="droite">Statut : <?= htmlspecialchars($statuts[$commande->statut] ?? str_replace('_', ' ', $commande->statut)) ?></p>
    </div>

    <div class="corps">
        <table>
            <thead>
                <tr>
                    <th>Produit</th>
                    <th class="centre">Qté</th>
                    <th class="droite">Prix unitaire</th>
                    <th class="droite">Sous-total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($commande->lignes as $ligne): ?>
                <tr>
                    <td class="poids"><?= htmlspecialchars((string) $ligne->produitLibelle) ?></td>
                    <td class="centre"><?= $ligne->quantite ?></td>
                    <td class="droite unitaire"><?= number_format($ligne->prixUnitaire, 0, ' ', ' ') ?> FCFA</td>
                    <td class="droite poids"><?= number_format($ligne->sousTotal, 0, ' ', ' ') ?> FCFA</td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="total-ligne">
            <tr>
                <td class="label">Total réglé</td>
                <td class="regle"><?= number_format($facture->montantTotal ?? $commande->total, 0, ' ', ' ') ?> FCFA</td>
            </tr>
        </table>

        <div class="pied">
            Route des Almadies, Dakar, Sénégal — +221 78 540 55 93 — Merci de votre confiance !
        </div>
    </div>
</div>
</body>
</html>