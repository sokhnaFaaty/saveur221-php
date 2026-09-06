<h1>Notre carte & menus</h1>

<?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
    <p><?= htmlspecialchars($flash['message']) ?></p>
<?php endif; ?>

<form method="get" action="/produits">
    <input type="text" name="q" placeholder="Rechercher un plat...">
    <button type="submit">Rechercher</button>
</form>

<?php if (hasRole('GERANT') || hasRole('ADMIN')): ?>
<form method="post" action="/produits" enctype="multipart/form-data">
    <input type="text" name="libelle" placeholder="Nom du plat" required>
    <input type="text" name="prix" placeholder="Prix" required>
    <input type="text" name="quantite_stock" placeholder="Stock initial" required>
    <input type="text" name="categorie_id" placeholder="ID categorie" required>
    <input type="file" name="image" accept="image/png,image/jpeg,image/webp">
    <button type="submit">Ajouter</button>
</form>
<?php endif; ?>

<ul>
<?php foreach ($produits as $produit): ?>
    <li>
        <a href="/produits/<?= $produit->id ?>"><?= htmlspecialchars($produit->libelle) ?></a>
        — <?= number_format($produit->prix, 0) ?> FCFA
        — <?= $produit->disponible() ? 'En stock' : 'Epuise' ?>

        <?php if (hasRole('GERANT') || hasRole('ADMIN')): ?>
        <form method="post" action="/produits/<?= $produit->id ?>/delete" style="display:inline">
            <button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
        </form>
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>