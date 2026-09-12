<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold">Gestion des Utilisateurs Staff</h1>
        <p class="text-sm text-gray-500">Module de gestion des accès Gérants et Admins du restaurant.</p>
    </div>
    <div class="flex items-center gap-3">
        <button type="button" onclick="ouvrirDrawerStaff()" class="px-4 py-2.5 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary-dark transition flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span class="hidden md:inline">Ajouter staff</span>
            <span class="md:hidden">Ajouter</span>
        </button>
        <a href="/staff/corbeille" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition flex items-center gap-2">
            <i class="fa-regular fa-trash-can"></i> Corbeille
        </a>
    </div>
</div>

<div id="overlay-staff" onclick="fermerDrawerStaff()" class="hidden fixed inset-0 bg-black/40 z-40"></div>

<aside id="drawer-staff" class="fixed top-0 right-0 h-full w-full max-w-md bg-white shadow-2xl z-50 translate-x-full transition-transform duration-300 flex flex-col">
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <div>
            <h2 class="font-extrabold text-lg">Ajouter un membre du staff</h2>
            <p class="text-xs text-gray-400">Nouveau compte Gérant ou Admin</p>
        </div>
        <button onclick="fermerDrawerStaff()" class="text-gray-400 hover:text-gray-700"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <form method="post" action="/staff" enctype="multipart/form-data" class="flex-1 overflow-y-auto px-6 py-5 space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Prénom</label>
            <input type="text" name="prenom" placeholder="Ex : Awa" value="<?= htmlspecialchars(ancienneValeur('prenom')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400 <?= erreurChamp('prenom') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('prenom')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Nom</label>
            <input type="text" name="nom" placeholder="Ex : Diop" value="<?= htmlspecialchars(ancienneValeur('nom')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400 <?= erreurChamp('nom') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('nom')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Email professionnel</label>
            <input type="email" name="email" placeholder="Ex : awa.diop@saveur221.sn" value="<?= htmlspecialchars(ancienneValeur('email')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400 <?= erreurChamp('email') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('email')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Téléphone</label>
            <input type="text" name="telephone" placeholder="Ex : 771234567" value="<?= htmlspecialchars(ancienneValeur('telephone')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400 <?= erreurChamp('telephone') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('telephone')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Adresse</label>
            <input type="text" name="adresse" placeholder="Ex : Almadies, Dakar" value="<?= htmlspecialchars(ancienneValeur('adresse')) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400 <?= erreurChamp('adresse') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('adresse')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
        </div>
        <div class="grid grid-cols-2 gap-3">
            <div>
                <label class="block text-sm font-semibold mb-1">Rôle assigné</label>
                <select name="role" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm bg-white <?= erreurChamp('role') !== '' ? 'border-red-500' : '' ?>">
                    <option value="GERANT" <?= ancienneValeur('role', 'GERANT') === 'GERANT' ? 'selected' : '' ?>>Gérant</option>
                    <option value="ADMIN" <?= ancienneValeur('role', 'GERANT') === 'ADMIN' ? 'selected' : '' ?>>Admin</option>
                </select>
                <?php if ($erreur = erreurChamp('role')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Statut compte</label>
                <select name="actif" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm bg-white">
                    <option value="1" <?= ancienneValeur('actif', '1') === '1' ? 'selected' : '' ?>>Actif</option>
                    <option value="0" <?= ancienneValeur('actif', '1') === '0' ? 'selected' : '' ?>>Inactif</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Mot de passe provisoire</label>
            <input type="password" name="mot_de_passe" placeholder="Minimum 6 caractères" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400 <?= erreurChamp('mot_de_passe') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('mot_de_passe')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-2">Photo de profil <span class="text-xs font-normal text-gray-400">(téléverser ou lien)</span></label>
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <input type="radio" name="image_option" value="file" id="opt_staff_file" checked class="accent-red-600">
                    <label for="opt_staff_file" class="text-sm font-semibold">Option A : Téléverser un fichier</label>
                </div>
                <div class="pl-6" id="bloc-staff-file">
                    <input type="file" name="image_file" accept="image/png,image/jpeg,image/webp" class="text-sm">
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="radio" name="image_option" value="url" id="opt_staff_url" class="accent-red-600">
                    <label for="opt_staff_url" class="text-sm font-semibold">Option B : Lien internet</label>
                </div>
                <div class="pl-6 hidden" id="bloc-staff-url">
                    <input type="text" name="image_url" placeholder="https://example.com/photo.jpg"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400">
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="button" onclick="fermerDrawerStaff()" class="flex-1 py-2.5 rounded-lg bg-white text-gray-900 border border-gray-500 text-sm font-semibold hover:bg-gray-50 transition">Annuler</button>
            <button type="submit" class="flex-1 py-2.5 rounded-lg bg-[#A8291A] text-white text-sm font-semibold hover:opacity-90 transition">Enregistrer</button>
        </div>
    </form>
</aside>

<script>
    if (document.querySelector('#drawer-staff p.text-red-500')) {
        ouvrirDrawerStaff();
    }
    function ouvrirDrawerStaff() {
        document.getElementById('overlay-staff').classList.remove('hidden');
        document.getElementById('drawer-staff').classList.remove('translate-x-full');
    }
    function fermerDrawerStaff() {
        document.getElementById('overlay-staff').classList.add('hidden');
        document.getElementById('drawer-staff').classList.add('translate-x-full');
    }
    document.querySelectorAll('input[name="image_option"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const fichier = document.getElementById('opt_staff_file').checked;
            document.getElementById('bloc-staff-file').classList.toggle('hidden', !fichier);
            document.getElementById('bloc-staff-url').classList.toggle('hidden', fichier);
            if (fichier) {
                document.querySelector('input[name="image_url"]').value = '';
            } else {
                document.querySelector('input[name="image_file"]').value = '';
            }
        });
    });
</script>

<div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1 mb-6 w-fit">
    <a href="?<?= http_build_query(array_merge($_GET, ['vue' => 'tableau'])) ?>"
       class="px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-2 <?= $vue === 'tableau' ? 'bg-white shadow-sm' : 'text-gray-500' ?>">
        <i class="fa-solid fa-list"></i> Tableau
    </a>
    <a href="?<?= http_build_query(array_merge($_GET, ['vue' => 'cartes'])) ?>"
       class="px-3 py-1.5 rounded-md text-sm font-semibold flex items-center gap-2 <?= $vue === 'cartes' ? 'bg-white shadow-sm' : 'text-gray-500' ?>">
        <i class="fa-solid fa-table-cells-large"></i> Cartes
    </a>
</div>

<?php if ($vue === 'tableau'): ?>
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
                            <button class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold hover:border-primary transition"><?= $u->actif ? 'Désactiver' : 'Activer' ?></button>
                        </form>
                        <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Supprimer ce compte staff",message:"Cette opération est irréversible.",cible:' . json_encode($u->prenom . ' ' . $u->nom) . ',actionUrl:"/staff/' . $u->id . '/delete"})', ENT_QUOTES, 'UTF-8') ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
                        <?php else: ?><span class="text-xs text-gray-400">(vous)</span><?php endif; ?>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($staff === []): ?><tr><td colspan="5" class="text-center text-gray-400 py-10">Aucun collaborateur.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($staff as $u): ?>
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="w-10 h-10 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold"><?= mb_substr($u->prenom, 0, 1) ?></span>
            <div>
                <p class="font-bold"><?= htmlspecialchars($u->prenom . ' ' . $u->nom) ?></p>
                <span class="text-xs font-bold px-2 py-0.5 rounded-full <?= $u->role === 'ADMIN' ? 'bg-purple-50 text-purple-700' : 'bg-amber-50 text-amber-700' ?>"><?= $u->role ?></span>
            </div>
        </div>
        <div class="space-y-1 text-sm text-gray-500 mb-4">
            <p class="truncate"><i class="fa-solid fa-envelope w-4 text-primary"></i> <?= htmlspecialchars($u->email) ?></p>
            <p><i class="fa-solid fa-phone w-4 text-primary"></i> <?= htmlspecialchars((string) $u->telephone) ?></p>
        </div>
        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
            <span class="text-xs font-semibold px-2 py-1 rounded-full <?= $u->actif ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' ?>"><?= $u->actif ? 'Actif' : 'Inactif' ?></span>
            <?php if ($u->id !== (int) $_SESSION['user']['id']): ?>
            <div class="flex items-center gap-2">
                <form method="post" action="/staff/<?= $u->id ?>/toggle">
                    <input type="hidden" name="actif" value="<?= $u->actif ? '0' : '1' ?>">
                    <button class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold hover:border-primary transition"><?= $u->actif ? 'Désactiver' : 'Activer' ?></button>
                </form>
                <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Supprimer ce compte staff",message:"Cette opération est irréversible.",cible:' . json_encode($u->prenom . ' ' . $u->nom) . ',actionUrl:"/staff/' . $u->id . '/delete"})', ENT_QUOTES, 'UTF-8') ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if ($staff === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucun collaborateur.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>
<?php effacerErreursFormulaire(); ?>