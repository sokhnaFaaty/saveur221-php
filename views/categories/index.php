<h1>Categories du menu</h1>

<?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
    <p><?= htmlspecialchars($flash['message']) ?></p>
<?php endif; ?>

<form method="post" action="/categories">
    <input type="text" name="libelle" placeholder="Nom de la categorie" required>
    <input type="text" name="description" placeholder="Description">
    <button type="submit">Creer</button>
</form>

<ul>
<?php foreach ($categories as $categorie): ?>
    <li>
        <?= htmlspecialchars($categorie->libelle) ?>
        <form method="post" action="/categories/<?= $categorie->id ?>/delete" style="display:inline">
            <button type="submit" onclick="return confirm('Supprimer ?')">Supprimer</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>