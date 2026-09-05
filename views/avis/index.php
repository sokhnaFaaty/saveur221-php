<h1>Moderation des avis</h1>
<?php foreach ($avis as $a): ?>
<div>
    <strong><?= htmlspecialchars($a->clientPrenom . ' ' . $a->clientNom) ?></strong>
    — <?= str_repeat('★', $a->note) ?> (<?= $a->note ?>/5)
    <p><?= htmlspecialchars((string) $a->commentaire) ?></p>
    <form method="post" action="/avis/<?= $a->id ?>/delete" style="display:inline">
        <button type="submit" onclick="return confirm('Supprimer cet avis ?')">Supprimer</button>
    </form>
</div>
<?php endforeach; ?>