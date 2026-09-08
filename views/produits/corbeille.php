<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-extrabold">Corbeille des plats</h1>
        <p class="text-sm text-gray-500">Plats supprimés : restaurez-les ou supprimez-les définitivement.</p>
    </div>
    <a href="/produits" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Retour aux plats
    </a>
</div>

<?php if ($produits === []): ?>
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <i class="fa-solid fa-trash-can text-4xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 font-semibold">La corbeille est vide.</p>
    </div>
<?php else: ?>
<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr>
                <th class="px-4 py-3">Plat</th><th class="px-4 py-3">Categorie</th><th class="px-4 py-3">Prix</th>
                <th class="px-4 py-3">Supprimé le</th><th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            <?php foreach ($produits as $plat): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <img src="<?= htmlspecialchars($plat->image ?: '/assets/img/maquettes/ThieboudienneRouge.jpg') ?>" class="w-10 h-10 rounded-lg object-cover">
                        <div>
                            <p class="font-bold text-gray-900"><?= htmlspecialchars($plat->libelle) ?></p>
                            <p class="text-xs text-gray-400">#<?= $plat->id ?></p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $plat->categorieLibelle) ?></td>
                <td class="px-4 py-3 font-extrabold text-primary"><?= number_format($plat->prix, 0, ' ', ' ') ?> FCFA</td>
                <td class="px-4 py-3 text-gray-500 text-xs"><?= htmlspecialchars((string) $plat->supprimeLe()) ?></td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <form method="post" action="/produits/<?= $plat->id ?>/restaurer">
                            <button class="px-3 py-1.5 rounded-lg border border-green-200 text-green-700 text-xs font-semibold hover:bg-green-50 transition">
                                <i class="fa-solid fa-rotate-left"></i> Restaurer
                            </button>
                        </form>
                        <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Supprimer définitivement",message:"Cette action est irréversible. Le plat sera retiré pour toujours.",cible:' . json_encode($plat->libelle) . ',actionUrl:"/produits/' . $plat->id . '/supprimer-definitivement"})', ENT_QUOTES, 'UTF-8') ?>"
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