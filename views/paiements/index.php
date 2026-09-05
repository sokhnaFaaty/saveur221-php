<h1>Caisse & reglements</h1>
<table border="1" cellpadding="6">
    <tr><th>Commande</th><th>Date</th><th>Moyen</th><th>Montant</th></tr>
    <?php foreach ($paiements as $p): ?>
    <tr>
        <td><a href="/commandes/<?= $p->commandeId ?>">#<?= $p->commandeId ?></a></td>
        <td><?= htmlspecialchars($p->datePaiement) ?></td>
        <td><?= htmlspecialchars($p->moyen) ?></td>
        <td><?= number_format($p->montant, 0) ?> FCFA</td>
    </tr>
    <?php endforeach; ?>
</table>