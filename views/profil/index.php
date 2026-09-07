<?php
/** @var \App\Models\Utilisateur|\App\Models\Client|null $profil */
$user    = $_SESSION['user'] ?? [];
$role    = $user['role'] ?? '';
$profil  = $profil ?? null;
$image   = $profil ? $profil->image : ($user['image'] ?? null);
$telephone = $profil ? $profil->telephone : '';
?>
<h1 class="text-2xl font-extrabold mb-1">Mon Profil Professionnel & Securite</h1>
<p class="text-sm text-gray-500 mb-6">Gerez vos informations de compte, coordonnees de contact, photo et mot de passe d'acces.</p>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-24">

    <form method="post" action="/profil" enctype="multipart/form-data" class="bg-white rounded-xl p-6 shadow-sm h-full">
        <h2 class="font-bold mb-4"><i class="fa-regular fa-user text-primary"></i> Mes Informations Personnelles</h2>

        <div class="space-y-3 text-sm">
            <div class="flex flex-col items-center gap-3 mb-4">
                <img id="apercu-photo"
                     src="<?= htmlspecialchars($image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>"
                     alt="Photo de profil"
                     class="w-24 h-24 rounded-full object-cover border-4 border-primary-light shadow">
                <label for="photo"
                       class="flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-light text-primary text-xs font-semibold cursor-pointer hover:bg-primary/20 transition">
                    <i class="fa-solid fa-camera"></i> Changer ma photo
                    <input type="file" id="photo" name="photo" accept="image/*" class="hidden" onchange="apercuPhoto(this)">
                </label>
            </div>

            <div>
                <label for="prenom" class="block font-semibold mb-1">Prenom <span class="text-primary">*</span></label>
                <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars((string) ($profil?->prenom ?? '')) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
            <div>
                <label for="nom" class="block font-semibold mb-1">Nom <span class="text-primary">*</span></label>
                <input type="text" id="nom" name="nom" value="<?= htmlspecialchars((string) ($profil?->nom ?? '')) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
            <div>
                <label for="email" class="block font-semibold mb-1">Email professionnel <span class="text-primary">*</span></label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars((string) ($profil?->email ?? '')) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
            <div>
                <label for="telephone" class="block font-semibold mb-1">Telephone <span class="text-primary">*</span></label>
                <input type="tel" id="telephone" name="telephone" value="<?= htmlspecialchars((string) $telephone) ?>"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>

            <div class="bg-primary-light rounded-lg px-3 py-2">
                <p class="text-xs text-gray-500">ROLE ATTRIBUE</p>
                <p class="font-bold text-primary"><?= htmlspecialchars($role) ?></p>
            </div>

            <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">
                Enregistrer les modifications
            </button>
        </div>
    </form>

    <form method="post" action="/profil/mot-de-passe" class="bg-white rounded-xl p-6 shadow-sm h-full">
        <h2 class="font-bold mb-4"><i class="fa-solid fa-lock text-primary"></i> Securite & Mot de Passe</h2>
        <div class="space-y-3 text-sm">
            <div>
                <label for="ancien_mot_de_passe" class="block font-semibold mb-1">Ancien mot de passe <span class="text-primary">*</span></label>
                <input type="password" id="ancien_mot_de_passe" name="ancien_mot_de_passe" autocomplete="current-password"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
            <div>
                <label for="nouveau_mot_de_passe" class="block font-semibold mb-1">Nouveau mot de passe <span class="text-primary">*</span></label>
                <input type="password" id="nouveau_mot_de_passe" name="nouveau_mot_de_passe" minlength="6" autocomplete="new-password"
                       placeholder="Au moins 6 caracteres"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
            <div>
                <label for="confirmation" class="block font-semibold mb-1">Confirmer le nouveau mot de passe <span class="text-primary">*</span></label>
                <input type="password" id="confirmation" name="confirmation" minlength="6" autocomplete="new-password"
                       class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition">
            </div>
            <button type="submit" class="w-full py-2.5 rounded-lg bg-gray-900 text-white font-semibold hover:bg-gray-800 transition">
                Mettre a jour le mot de passe
            </button>
        </div>
    </form>
</div>

<script>
    function apercuPhoto(champ) {
        const fichier = champ.files[0];
        if (!fichier) return;
        const lecteur = new FileReader();
        lecteur.onload = (e) => { document.getElementById('apercu-photo').src = e.target.result; };
        lecteur.readAsDataURL(fichier);
    }
</script>
