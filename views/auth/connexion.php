<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - Saveur 221</title>
</head>
<body>
    <h1>Connexion</h1>

    <?php if ($flash = $_SESSION['flash'] ?? null): unset($_SESSION['flash']); ?>
        <p><?= htmlspecialchars($flash['message']) ?></p>
    <?php endif; ?>

    <form method="post" action="/connexion">
        <label>Email : <input type="email" name="email" required></label><br>
        <label>Mot de passe : <input type="password" name="mot_de_passe" required></label><br>
        <label><input type="checkbox" name="se_souvenir" value="1"> Se souvenir de moi</label><br>
        <button type="submit">Se connecter</button>
    </form>
</body>
</html>