<h1 class="text-white text-2xl md:text-[26px] font-extrabold text-center mb-4">Creez votre Compte Client</h1>

<div class="w-full max-w-xl bg-white rounded-2xl shadow-2xl p-5 md:p-6">

    <div class="flex flex-col items-center mb-3">
        <span class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-lg">
            <i class="fa-solid fa-utensils text-white text-lg"></i>
        </span>
        <p class="text-xs text-gray-500 text-center mt-2.5">
            Rejoignez la communauté Saveur221 et bénéficiez de la livraison rapide a Dakar et de promotions exclusives.
        </p>
    </div>

    <form method="post" action="/inscription" enctype="multipart/form-data" class="space-y-3">
        <div class="grid sm:grid-cols-2 gap-3">
            <div>
                <label for="nom_complet" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Nom complet <span class="text-primary">*</span>
                </label>
                <input type="text" id="nom_complet" name="nom_complet" required placeholder="Ex: Aminata Ndiaye"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>

            <div>
                <label for="telephone" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Telephone <span class="font-normal normal-case">(221)</span> <span class="text-primary">*</span>
                </label>
                <input type="tel" id="telephone" name="telephone" required autocomplete="tel" placeholder="Ex: 77 645 22 10"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>

            <div class="sm:col-span-2">
                <label for="email" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Adresse email <span class="text-primary">*</span>
                </label>
                <input type="email" id="email" name="email" required autocomplete="email" placeholder="Ex: aminata.ndiaye@gmail.com"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>

            <div>
                <label for="quartier_de_livraison" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Quartier de livraison <span class="text-primary">*</span>
                </label>
                <select id="quartier_de_livraison" name="quartier_de_livraison" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
                    <option value="Almadies" selected>Almadies</option>
                    <option value="Plateau">Plateau</option>
                    <option value="Point E">Point E</option>
                    <option value="Yoff">Yoff</option>
                    <option value="Ouakam">Ouakam</option>
                    <option value="Ngor">Ngor</option>
                    <option value="Mermoz">Mermoz</option>
                    <option value="Fann">Fann</option>
                    <option value="Mamelles">Mamelles</option>
                    <option value="Sacré Coeur">Sacre Cœur</option>
                    <option value="Grand Yoff">Grand Yoff</option>
                    <option value="Liberté 6">Liberte 6</option>
                    <option value="Hann">Hann</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Image <span class="font-normal normal-case">(optionnel)</span>
                </label>
                <label for="image"
                       class="flex items-center justify-center gap-2 w-full px-4 py-2 rounded-lg border border-dashed border-gray-300
                              text-sm text-gray-500 cursor-pointer hover:border-primary hover:text-primary transition">
                    <i class="fa-solid fa-image"></i>
                    <span id="label-image">Choisir un fichier</span>
                    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" class="hidden">
                </label>
            </div>

            <div>
                <label for="mot_de_passe" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Mot de passe <span class="text-primary">*</span>
                </label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required minlength="8"
                       autocomplete="new-password" placeholder="Au moins 8 caracteres"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>

            <div>
                <label for="confirmation" class="block text-[11px] font-bold uppercase tracking-wide text-gray-700 mb-1">
                    Confirmer le mot de passe <span class="text-primary">*</span>
                </label>
                <input type="password" id="confirmation" name="confirmation" required minlength="8"
                       autocomplete="new-password" placeholder="Repeter le mot de passe"
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 text-sm placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
        </div>

        <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg bg-primary text-white font-bold text-sm
                       hover:bg-primary-dark transition shadow-lg">
            <i class="fa-solid fa-arrow-right-to-bracket"></i> S'inscrire
        </button>
    </form>

    <p class="text-center text-sm text-gray-500 mt-4">
        Vous avez déjà un compte? 
        <a href="/connexion" class="text-red-600 font-semibold hover:underline">Connectez-vous</a>
    </p>
</div>

<p class="mt-4">
    <a href="/" class="inline-flex items-center gap-2 text-white font-semibold hover:underline text-sm">
        <i class="fa-solid fa-arrow-left"></i> Accueil
    </a>
</p>

<script>
    const champImage = document.getElementById('image');
    const labelImage = document.getElementById('label-image');
    champImage.addEventListener('change', () => {
        labelImage.textContent = champImage.files[0] ? champImage.files[0].name : 'Choisir un fichier';
    });
</script>