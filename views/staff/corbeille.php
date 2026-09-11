<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-extrabold">Corbeille du staff</h1>
        <p class="text-sm text-gray-500">Comptes supprimés : restaurez-les ou supprimez-les définitivement.</p>
    </div>
    <a href="/staff" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Retour au staff
    </a>
</div>

<?php if ($staff === []): ?>
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <i class="fa-solid fa-trash-can text-4xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 font-semibold">La corbeille est vide.</p>
    </div>
<?php else: ?>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Membre</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Rôle</th>
                <th class="px-4 py-3">Supprimé le</th><th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach ($staff as $u): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold shrink-0">
                            <?= strtoupper(mb_substr($u->prenom, 0, 1)) ?><?= strtoupper(mb_substr($u->nom, 0, 1)) ?>
                        </div>
                        <p class="font-bold text-gray-900"><?= htmlspecialchars($u->nomComplet()) ?></p>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($u->email) ?></td>
                <td class="px-4 py-3">
                    <span class="text-xs font-bold px-2.5 py-1 rounded-full <?= $u->role === 'ADMIN' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' ?>">
                        <?= $u->role === 'ADMIN' ? 'Administrateur' : 'Gérant' ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs"><?= htmlspecialchars($u->supprimeLe()) ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <form method="post" action="/staff/<?= $u->id ?>/restaurer">
                            <button class="px-3 py-1.5 rounded-lg border border-green-200 text-green-700 text-xs font-semibold hover:bg-green-50 transition">
                                <i class="fa-solid fa-rotate-left"></i> Restaurer
                            </button>
                        </form>
                        <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Supprimer définitivement",message:"Cette action est irréversible.",cible:' . json_encode($u->nomComplet()) . ',actionUrl:"/staff/' . $u->id . '/supprimer-definitivement"})', ENT_QUOTES, 'UTF-8') ?>"
                                class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition">
                            <i class="fa-regular fa-trash-can"></i> Supprimer définitivement
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>