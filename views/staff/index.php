<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Gestion des Utilisateurs Staff</h1>
        <p class="text-sm text-gray-500">Module de gestion des acces Gerants et Admins du restaurant.</p>
    </div>
</div>

<form method="post" action="/staff" class="bg-white rounded-xl p-5 shadow-sm mb-6 grid md:grid-cols-3 gap-3">
    <input type="text" name="prenom" placeholder="Prenom" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
    <input type="text" name="nom" placeholder="Nom" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
    <input type="text" name="email" placeholder="Email professionnel" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
    <input type="text" name="telephone" placeholder="Telephone" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
    <select name="role" class="px-3 py-2 rounded-lg border border-gray-200 text-sm bg-white">
        <option value="GERANT">GERANT</option><option value="ADMIN">ADMIN</option>
    </select>
    <input type="password" name="mot_de_passe" placeholder="Mot de passe" class="px-3 py-2 rounded-lg border border-gray-200 text-sm">
    <button type="submit" class="md:col-span-3 py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition">Creer un compte Staff</button>
</form>

<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Collaborateur</th><th class="px-4 py-3">Role</th><th class="px-4 py-3">Contact</th><th class="px-4 py-3">Statut</th><th class="px-4 py-3">Actions</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($staff as $u): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-semibold"><?= htmlspecialchars($u->prenom . ' ' . $u->nom) ?><br><span class="text-xs text-gray-400 font-normal"><?= htmlspecialchars($u->email) ?></span></td>
                <td class="px-4 py-3"><span class="text-xs font-bold px-2 py-1 rounded-full <?= $u->role === 'ADMIN' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700' ?>"><?= $u->role ?></span></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $u->telephone) ?></td>
                <td class="px-4 py-3"><span class="text-xs font-semibold px-2 py-1 rounded-full <?= $u->actif ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' ?>"><?= $u->actif ? 'Actif' : 'Inactif' ?></span></td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2">
                        <?php if ($u->id !== (int) $_SESSION['user']['id']): ?>
                        <form method="post" action="/staff/<?= $u->id ?>/toggle">
                            <input type="hidden" name="actif" value="<?= $u->actif ? '0' : '1' ?>">
                            <button class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold hover:border-primary transition"><?= $u->actif ? 'Desactiver' : 'Activer' ?></button>
                        </form>
                        <form method="post" action="/staff/<?= $u->id ?>/delete" onsubmit="return confirm('Supprimer ?')">
                            <button class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                        </form>
                        <?php else: ?><span class="text-xs text-gray-400">(vous)</span><?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>