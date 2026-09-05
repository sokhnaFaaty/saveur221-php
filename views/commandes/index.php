<h1>Gestion des commandes</h1>
<?php foreach ($commandes as $commande): ?>
<div>
    <strong><?= htmlspecialchars($commande->numCommande) ?></strong> — <?= htmlspecialchars($commande->statut) ?>
    <form method="post" action="/commandes/<?= $commande->id ?>/statut" style="display:inline">
        <select name="statut">
            <?php foreach (['EN_ATTENTE','EN_PREPARATION','PRETE','RETIREE','ANNULEE'] as $s): ?>
                <option value="<?= $s ?>" <?= $s === $commande->statut ? 'selected' : '' ?>><?= $s ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Mettre a jour</button>
    </form>
</div>
<?php endforeach; ?>