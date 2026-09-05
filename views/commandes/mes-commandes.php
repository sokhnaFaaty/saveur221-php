<h1>Mes commandes</h1>
<ul>
<?php foreach ($commandes as $commande): ?>
    <li>
        <a href="/commandes/<?= $commande->id ?>"><?= htmlspecialchars($commande->numCommande) ?></a>
        — <?= number_format($commande->total, 0) ?> FCFA — <?= htmlspecialchars($commande->statut) ?>
    </li>
<?php endforeach; ?>
</ul>