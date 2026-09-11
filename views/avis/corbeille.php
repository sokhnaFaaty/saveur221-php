<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <div>
        <h1 class="text-2xl font-extrabold">Corbeille des avis</h1>
        <p class="text-sm text-gray-500">Avis supprimés : restaurez-les ou supprimez-les définitivement.</p>
    </div>
    <a href="/avis" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold hover:bg-gray-50 transition">
        <i class="fa-solid fa-arrow-left mr-1"></i> Retour aux avis
    </a>
</div>

<?php if ($avis === []): ?>
    <div class="bg-white rounded-xl shadow-sm p-12 text-center">
        <i class="fa-solid fa-trash-can text-4xl text-gray-200 mb-4"></i>
        <p class="text-gray-400 font-semibold">La corbeille est vide.</p>
    </div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <?php foreach ($avis as $a): ?>
    <div class="bg-white rounded-xl shadow-sm p-5 flex flex-col">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-bold text-gray-900"><?= htmlspecialchars($a->clientPrenom . ' ' . $a->clientNom) ?></span>
            <span class="text-amber-500 text-sm flex items-center gap-1">
                <?= str_repeat('<i class="fa-solid fa-star"></i>', $a->note) ?><?= str_repeat('<i class="fa-regular fa-star"></i>', 5 - $a->note) ?>
            </span>
        </div>
        <p class="text-xs text-gray-400 mb-3">Commandé #<?= $a->commandeId ?> · Supprimé le <?= htmlspecialchars($a->supprimeLe()) ?></p>
        <p class="text-sm text-gray-600 italic mb-4 flex-1">"<?= htmlspecialchars((string) $a->commentaire) ?>"</p>
        <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-50">
            <form method="post" action="/avis/<?= $a->id ?>/restaurer">
                <button class="px-3 py-1.5 rounded-lg border border-green-200 text-green-700 text-xs font-semibold hover:bg-green-50 transition">
                    <i class="fa-solid fa-rotate-left"></i> Restaurer
                </button>
            </form>
            <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Supprimer définitivement",message:"Cette action est irréversible.",cible:' . json_encode($a->clientPrenom . ' ' . $a->clientNom) . ',actionUrl:"/avis/' . $a->id . '/supprimer-definitivement"})', ENT_QUOTES, 'UTF-8') ?>"
                    class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-xs font-semibold hover:bg-red-50 transition">
                <i class="fa-regular fa-trash-can"></i> Supprimer définitivement
            </button>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>