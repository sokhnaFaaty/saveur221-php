<?php
/** @var \App\Models\Commande $commande */
/** @var \App\Models\Facture|null $facture */
?>
<h1>Facture <?= htmlspecialchars($facture->numero ?? '') ?></h1>
<p>Date : <?= htmlspecialchars($facture->dateEmission ?? '') ?></p>
<p>Commande : <?= htmlspecialchars($commande->numCommande) ?></p>

<table border="1" cellpadding="6">
    <tr><th>Produit</th><th>Quantite</th><th>Prix unitaire</th><th>Sous-total</th></tr>
    <?php foreach ($commande->lignes as $ligne): ?>
    <tr>
        <td><?= htmlspecialchars((string) $ligne->produitLibelle) ?></td>
        <td><?= $ligne->quantite ?></td>
        <td><?= number_format($ligne->prixUnitaire, 0) ?> FCFA</td>
        <td><?= number_format($ligne->sousTotal, 0) ?> FCFA</td>
    </tr>
    <?php endforeach; ?>
</table>

<p><strong>Total : <?= number_format($facture->montantTotal ?? $commande->total, 0) ?> FCFA</strong></p>