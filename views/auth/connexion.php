<div class="max-w-md mx-auto my-16">
    <div class="bg-white border border-gray-100 rounded-2xl shadow-lg p-8">
        <div class="text-center mb-6">
            <span class="inline-flex w-12 h-12 bg-primary rounded-xl items-center justify-center text-white font-bold text-lg mb-3">S</span>
            <h1 class="text-xl font-extrabold">Connexion</h1>
            <p class="text-sm text-gray-500 mt-1">Accedez a votre espace Saveur 221</p>
        </div>

        <form method="post" action="/connexion" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-semibold mb-1">Adresse email</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
            </div>

            <div>
                <label for="mot_de_passe" class="block text-sm font-semibold mb-1">Mot de passe</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition">
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                <input type="checkbox" name="se_souvenir" value="1" class="rounded border-gray-300 text-primary focus:ring-primary">
                Se souvenir de moi
            </label>

            <button type="submit"
                    class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold text-sm
                           hover:bg-primary-dark transition">
                Se connecter
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Pas encore de compte ?
            <a href="/inscription" class="text-primary font-semibold hover:underline">Creer un compte</a>
        </p>
    </div>
</div>