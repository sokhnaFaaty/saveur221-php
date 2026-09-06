<div class="max-w-lg mx-auto my-16">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-8">
        <div class="text-center mb-6">
            <span class="inline-flex w-12 h-12 bg-primary rounded-xl items-center justify-center text-white font-bold text-lg mb-3">S</span>
            <h1 class="text-xl font-extrabold">Creez votre compte</h1>
            <p class="text-sm text-gray-500 mt-1">Rejoignez Saveur 221 en quelques secondes</p>
        </div>

        <form method="post" action="/inscription" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="prenom" class="block text-sm font-semibold mb-1">Prenom</label>
                    <input type="text" id="prenom" name="prenom" required
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
                <div>
                    <label for="nom" class="block text-sm font-semibold mb-1">Nom</label>
                    <input type="text" id="nom" name="nom" required
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
                </div>
            </div>

            <div>
                <label for="telephone" class="block text-sm font-semibold mb-1">Telephone</label>
                <input type="text" id="telephone" name="telephone" required placeholder="77XXXXXXX"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
            </div>

            <div>
                <label for="adresse" class="block text-sm font-semibold mb-1">Adresse <span class="text-gray-400 font-normal">(optionnel)</span></label>
                <input type="text" id="adresse" name="adresse"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Adresse email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
            </div>

            <div>
                <label for="mot_de_passe" class="block text-sm font-semibold mb-1">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="6"
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
                <p class="text-xs text-gray-400 mt-1">6 caracteres minimum</p>
            </div>

            <button type="submit"
                    class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold text-sm
                           hover:bg-primary-dark transition">
                Creer mon compte
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Deja un compte ?
            <a href="/connexion" class="text-primary font-semibold hover:underline">Se connecter</a>
        </p>
    </div>
</div>