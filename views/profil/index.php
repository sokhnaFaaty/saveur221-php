<?php
/** @var \App\Models\Utilisateur|\App\Models\Client|null $profil */
$user      = $_SESSION['user'] ?? [];
$role      = $user['role'] ?? '';
$profil    = $profil ?? null;
$image     = $profil ? $profil->image : ($user['image'] ?? null);
$telephone = $profil ? $profil->telephone : '';
$adresse   = $profil && method_exists($profil, 'adresse') ? $profil->adresse : '';
?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <form method="post" action="/profil" enctype="multipart/form-data" class="bg-white rounded-xl p-6 shadow-sm h-full">
        <h2 class="font-bold mb-5"><i class="fa-regular fa-user text-primary"></i> Mes Informations Personnelles</h2>

        <div class="flex items-center gap-4 mb-5">
            <img id="apercu-photo"
                 src="<?= htmlspecialchars($image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>"
                 alt="Photo de profil"
                 class="w-16 h-16 rounded-full object-cover border-2 border-primary-light shadow shrink-0">
            <div class="text-sm">
                <label for="photo" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-primary-light text-primary text-xs font-semibold cursor-pointer hover:bg-primary/20 transition w-fit">
                    <i class="fa-solid fa-camera"></i> Changer ma photo
                    <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="apercuPhoto(this)">
                </label>
                <p class="text-[11px] text-gray-400 mt-1">JPG, PNG ou WebP (2 Mo max)</p>
            </div>
        </div>

        <div class="space-y-3 text-sm">
            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label for="prenom" class="block font-semibold mb-1">Prénom <span class="text-primary">*</span></label>
                    <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars(ancienneValeur('prenom', (string) ($profil?->prenom ?? ''))) ?>"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('prenom') !== '' ? 'border-red-500' : '' ?>">
                    <?php if ($erreur = erreurChamp('prenom')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
                </div>
                <div>
                    <label for="nom" class="block font-semibold mb-1">Nom <span class="text-primary">*</span></label>
                    <input type="text" id="nom" name="nom" value="<?= htmlspecialchars(ancienneValeur('nom', (string) ($profil?->nom ?? ''))) ?>"
                           class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('nom') !== '' ? 'border-red-500' : '' ?>">
                    <?php if ($erreur = erreurChamp('nom')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
                </div>
            </div>

            <div>
                <label for="adresse" class="block font-semibold mb-1">Quartier / Adresse</label>
                <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars(ancienneValeur('adresse', (string) $adresse)) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('adresse') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('adresse')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="email" class="block font-semibold mb-1">Email professionnel <span class="text-primary">*</span></label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars(ancienneValeur('email', (string) ($profil?->email ?? ''))) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('email') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('email')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="telephone" class="block font-semibold mb-1">Téléphone <span class="text-primary">*</span></label>
                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars(ancienneValeur('telephone', (string) $telephone)) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('telephone') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('telephone')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>

            <div class="bg-primary-light rounded-lg px-3 py-2" style="background-color:#FBECEA">
                <p class="text-xs text-primary font-bold uppercase tracking-wider" style="color:#A8291A">Rôle attribué</p>
                <p class="font-extrabold text-lg text-primary" style="color:#A8291A"><?= htmlspecialchars($role !== '' ? $role : 'CLIENT') ?></p>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg text-white font-semibold transition shadow-sm" style="background-color:#A8291A">
                Enregistrer les modifications
            </button>
        </div>
    </form>

    <form method="post" action="/profil/mot-de-passe" class="bg-white rounded-xl p-6 shadow-sm h-full">
        <h2 class="font-bold mb-5"><i class="fa-solid fa-lock text-primary"></i> Sécurité & Mot de Passe</h2>
        <div class="space-y-3 text-sm">
            <div>
                <label for="ancien_mot_de_passe" class="block font-semibold mb-1">Ancien mot de passe <span class="text-primary">*</span></label>
                <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" autocomplete="current-password"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('ancien_mot_de_passe') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('ancien_mot_de_passe')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="nouveau_mot_de_passe" class="block font-semibold mb-1">Nouveau mot de passe <span class="text-primary">*</span></label>
                <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" minlength="6" autocomplete="new-password"
                       placeholder="Au moins 6 caractères"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('nouveau_mot_de_passe') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('nouveau_mot_de_passe')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
            <div>
                <label for="confirmation" class="block font-semibold mb-1">Confirmer le nouveau mot de passe <span class="text-primary">*</span></label>
                <input type="password" id="confirmation" name="confirmation" minlength="6" autocomplete="new-password"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition <?= erreurChamp('confirmation') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('confirmation')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
            <button type="submit" class="w-full py-2.5 rounded-lg bg-gray-900 text-white font-semibold hover:bg-gray-800 transition">
                Mettre à jour le mot de passe
            </button>
        </div>
    </form>
</div>

<?php effacerErreursFormulaire(); ?>

<script>
    function apercuPhoto(champ) {
        const fichier = champ.files[0];
        if (!fichier) return;
        const lecteur = new FileReader();
        lecteur.onload = (e) => { document.getElementById('apercu-photo').src = e.target.result; };
        lecteur.readAsDataURL(fichier);
    }
</script>
