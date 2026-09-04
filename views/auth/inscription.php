<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Saveur 221</title>
</head>
<body>
    <h1>Inscription</h1>

    <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
        <p><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="post" action="/inscription">
        <label>Nom : <input type="text" name="nom" required></label><br>
        <label>Prenom : <input type="text" name="prenom" required></label><br>
        <label>Telephone : <input type="text" name="telephone" required placeholder="77XXXXXXX"></label><br>
        <label>Adresse : <input type="text" name="adresse"></label><br>
        <label>Email : <input type="email" name="email" required></label><br>
        <label>Mot de passe : <input type="password" name="mot_de_passe" required></label><br>
        <button type="submit">Creer mon compte</button>
    </form>
</body>
</html>