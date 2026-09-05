<?php /** @var \App\Models\Produit $produit */ ?>

<a href="/produits">&larr; Retour a la carte</a>

<h1><?= htmlspecialchars($produit->libelle) ?></h1>
<p><?= htmlspecialchars((string) $produit->description) ?></p>
<p>Prix : <?= number_format($produit->prix, 0) ?> FCFA</p>
<p>Statut : <?= $produit->disponible() ? 'En stock' : 'Epuise' ?></p>