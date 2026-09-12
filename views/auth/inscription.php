<div class="w-full flex flex-col items-center px-6 py-4">

<div class="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-5 md:p-7">

    <div class="text-center mb-6">
        <span class="inline-flex w-12 h-12 bg-primary rounded-2xl items-center justify-center shadow-lg mb-3">
            <i class="fa-solid fa-utensils text-white text-lg"></i>
        </span>
        <h1 class="text-2xl md:text-[26px] font-extrabold text-gray-800 leading-tight">Créez votre Compte Client</h1>
        <p class="text-sm text-gray-500 mt-2">Rejoignez Saveur 221 et profitez de nos services.</p>
    </div>

    <form method="post" action="/inscription" enctype="multipart/form-data" class="space-y-3">

        <div>
            <label for="nom_complet" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Nom complet <span class="text-primary">*</span>
            </label>
            <input type="text" id="nom_complet" name="nom_complet" placeholder="Ex: Arminia Ndiaye" autocomplete="name"
                   value="<?= htmlspecialchars(ancienneValeur('nom_complet')) ?>"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('nom_complet') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('nom_complet')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="telephone" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Téléphone <span class="font-normal normal-case">(221)</span> <span class="text-primary">*</span>
            </label>
            <input type="tel" id="telephone" name="telephone" autocomplete="tel" placeholder="Ex: 77 645 22 10"
                   value="<?= htmlspecialchars(ancienneValeur('telephone')) ?>"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('telephone') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('telephone')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="email" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Adresse email <span class="text-primary">*</span>
            </label>
            <input type="email" id="email" name="email" autocomplete="email" placeholder="Ex: arminia.ndiaye@gmail.com"
                   value="<?= htmlspecialchars(ancienneValeur('email')) ?>"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('email') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('email')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label for="quartier_de_livraison" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Quartier de livraison (Dakar) <span class="text-primary">*</span>
            </label>
            <select id="quartier_de_livraison" name="quartier_de_livraison"
                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm bg-white
                           focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('quartier_de_livraison') !== '' ? 'border-red-500' : '' ?>">
                <option value="Almadies" <?= ancienneValeur('quartier_de_livraison') === 'Almadies' ? 'selected' : '' ?>>Almadies</option>
                <option value="Plateau" <?= ancienneValeur('quartier_de_livraison') === 'Plateau' ? 'selected' : '' ?>>Plateau</option>
                <option value="Point E" <?= ancienneValeur('quartier_de_livraison') === 'Point E' ? 'selected' : '' ?>>Point E</option>
                <option value="Yoff" <?= ancienneValeur('quartier_de_livraison') === 'Yoff' ? 'selected' : '' ?>>Yoff</option>
                <option value="Ouakam" <?= ancienneValeur('quartier_de_livraison') === 'Ouakam' ? 'selected' : '' ?>>Ouakam</option>
                <option value="Ngor" <?= ancienneValeur('quartier_de_livraison') === 'Ngor' ? 'selected' : '' ?>>Ngor</option>
                <option value="Mermoz" <?= ancienneValeur('quartier_de_livraison') === 'Mermoz' ? 'selected' : '' ?>>Mermoz</option>
                <option value="Fann" <?= ancienneValeur('quartier_de_livraison') === 'Fann' ? 'selected' : '' ?>>Fann</option>
                <option value="Mamelles" <?= ancienneValeur('quartier_de_livraison') === 'Mamelles' ? 'selected' : '' ?>>Mamelles</option>
                <option value="Sacré Coeur" <?= ancienneValeur('quartier_de_livraison') === 'Sacré Coeur' ? 'selected' : '' ?>>Sacré Coeur</option>
                <option value="Grand Yoff" <?= ancienneValeur('quartier_de_livraison') === 'Grand Yoff' ? 'selected' : '' ?>>Grand Yoff</option>
                <option value="Liberté 6" <?= ancienneValeur('quartier_de_livraison') === 'Liberté 6' ? 'selected' : '' ?>>Liberte 6</option>
                <option value="Hann" <?= ancienneValeur('quartier_de_livraison') === 'Hann' ? 'selected' : '' ?>>Hann</option>
            </select>
            <?php if ($erreur = erreurChamp('quartier_de_livraison')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="image" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Photo de profil
            </label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp"
                   class="w-full text-sm text-gray-500 file:mr-3 file:px-3 file:py-2 file:rounded-lg file:border-0
                          file:bg-primary file:text-white file:text-sm file:font-semibold
                          hover:file:bg-primary-dark file:cursor-pointer cursor-pointer transition">
        </div>
    </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
            <label for="mot_de_passe" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Mot de passe <span class="text-primary">*</span>
            </label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" minlength="6"
                   autocomplete="new-password" placeholder="Au moins 6 caractères"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('mot_de_passe') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('mot_de_passe')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="confirmation" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                Confirmer le mot de passe <span class="text-primary">*</span>
            </label>
            <input type="password" id="confirmation" name="confirmation" minlength="6"
                   autocomplete="new-password" placeholder="Répéter"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('confirmation') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('confirmation')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>
    </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-primary text-white font-bold text-sm
                       hover:bg-primary-dark transition shadow-lg">
            <i class="fa-solid fa-user-plus"></i> S'inscrire
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-5">
        Vous avez déjà un compte ?
        <a href="/connexion" class="text-primary font-semibold hover:underline">Connectez-vous</a>
    </p>
</div>

<p class="mt-4">
    <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition text-sm shadow">
        <i class="fa-solid fa-arrow-left"></i> Accueil
    </a>
</p>

<?php effacerErreursFormulaire(); ?>

</div>