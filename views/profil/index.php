<?php $user = $_SESSION['user']; ?>
<h1 class="text-2xl font-extrabold mb-1">Mon Profil Professionnel & Securite</h1>
<p class="text-sm text-gray-500 mb-6">Gerez vos informations de compte, coordonnees de contact et mot de passe d'acces.</p>

<div class="grid md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h2 class="font-bold mb-4"><i class="fa-regular fa-user text-primary"></i> Mes Informations Personnelles</h2>
        <div class="space-y-3 text-sm">
            <div><label class="block font-semibold mb-1">Prenom</label><input type="text" value="<?= htmlspecialchars($user['prenom']) ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200"></div>
            <div><label class="block font-semibold mb-1">Nom</label><input type="text" value="<?= htmlspecialchars($user['nom']) ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200"></div>
            <div><label class="block font-semibold mb-1">Email professionnel</label><input type="text" value="<?= htmlspecialchars($user['email']) ?>" class="w-full px-3 py-2 rounded-lg border border-gray-200"></div>
            <div class="bg-primary-light rounded-lg px-3 py-2"><p class="text-xs text-gray-500">ROLE ATTRIBUE</p><p class="font-bold text-primary"><?= htmlspecialchars($user['role']) ?></p></div>
            <button class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold hover:bg-primary-dark transition">Enregistrer les modifications</button>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 shadow-sm">
        <h2 class="font-bold mb-4"><i class="fa-solid fa-lock text-primary"></i> Securite & Mot de Passe</h2>
        <div class="space-y-3 text-sm">
            <div><label class="block font-semibold mb-1">Ancien mot de passe</label><input type="password" class="w-full px-3 py-2 rounded-lg border border-gray-200"></div>
            <div><label class="block font-semibold mb-1">Nouveau mot de passe</label><input type="password" class="w-full px-3 py-2 rounded-lg border border-gray-200"></div>
            <div><label class="block font-semibold mb-1">Confirmer le nouveau mot de passe</label><input type="password" class="w-full px-3 py-2 rounded-lg border border-gray-200"></div>
            <button class="w-full py-2.5 rounded-lg bg-gray-900 text-white font-semibold hover:bg-gray-800 transition">Mettre a jour le mot de passe</button>
        </div>
    </div>
</div>