<h1 class="text-black text-2xl md:text-3xl font-extrabold text-center mb-6">Connexion</h1>

<div class="w-full max-w-md bg-white rounded-2xl shadow-2xl p-6 md:p-8">

    <div class="flex flex-col items-center mb-4">
        <span class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-utensils text-white text-xl"></i>
        </span>
        <p class="text-sm text-gray-500 text-center mt-4">
            Accédez au suivi de vos commandes, vos reçus de paiements et vos adresses.
        </p>
    </div>

    <form method="post" action="/connexion" class="space-y-3">
        <div>
            <label for="email" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1.5">
                Email ou Téléphone <span class="font-normal normal-case">(+221)</span>
            </label>
            <input type="text" id="email" name="email" autocomplete="username" placeholder="aminata.ndiaye@gmail.com"
                   value="<?= htmlspecialchars(ancienneValeur('email')) ?>"
                   class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                          focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('email') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('email')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>

        <div>
            <label for="mot_de_passe" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1.5">
                Mot de passe
            </label>
            <div class="relative">
                <input type="password" id="mot_de_passe" name="mot_de_passe" autocomplete="current-password" placeholder="............"
                       class="w-full px-4 py-2.5 pr-12 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('mot_de_passe') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('mot_de_passe')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
                <?php endif; ?>
                <button type="button" data-pw-toggle="mot_de_passe" aria-label="Afficher le mot de passe"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <label class="flex items-center gap-2.5 cursor-pointer select-none">
            <input type="checkbox" name="se_souvenir" value="1"
                   class="w-4 h-4 rounded border-gray-300 accent-blue-600">
            <span class="text-sm text-gray-600">Se souvenir de moi</span>
        </label>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-3 rounded-lg bg-primary text-white font-bold text-sm
                       hover:bg-primary-dark transition shadow-lg">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> Se connecter
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-6">
        Vous n'avez pas encore de compte? 
        <a href="/inscription" class="text-red-600 font-semibold hover:underline">Créer un compte client</a>
    </p>
</div>

<p class="mt-6">
    <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition text-sm shadow">
        <i class="fa-solid fa-arrow-left"></i> Accueil
    </a>
</p>

<?php effacerErreursFormulaire(); ?>

<script>
    document.querySelectorAll('[data-pw-toggle]').forEach((bouton) => {
        bouton.addEventListener('click', () => {
            const champ = document.getElementById(bouton.dataset.pwToggle);
            if (!champ) return;
            const masque = champ.type === 'password';
            champ.type = masque ? 'text' : 'password';
            bouton.querySelector('i').className = masque ? 'fa-solid fa-eye-slash' : 'fa-solid fa-eye';
        });
    });
</script>