<?php /** @var \App\Models\Commande $commande */ ?>
<h1><?= htmlspecialchars($commande->numCommande) ?></h1>
<p>Statut : <?= htmlspecialchars($commande->statut) ?></p>
<p>Total : <?= number_format($commande->total, 0) ?> FCFA</p>
<ul>
<?php foreach ($commande->lignes as $ligne): ?>
    <li><?= $ligne->quantite ?>x <?= htmlspecialchars((string) $ligne->produitLibelle) ?> — <?= number_format($ligne->sousTotal, 0) ?> FCFA</li>
<?php endforeach; ?>
</ul>