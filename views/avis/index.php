<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-extrabold mb-1">Moderation des Avis Clients</h1>
        <p class="text-sm text-gray-500">Consultation, suivi de la satisfaction et moderation.</p>
    </div>
    <a href="/avis/corbeille" class="px-4 py-2.5 rounded-lg border border-gray-200 text-sm font-semibold text-gray-600 hover:bg-gray-50 transition flex items-center gap-2">
        <i class="fa-regular fa-trash-can"></i> Corbeille
    </a>
</div>

<div class="grid md:grid-cols-2 gap-5">
<?php foreach ($avis as $a): ?>
    <div class="bg-white rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-2">
            <p class="font-bold"><?= htmlspecialchars($a->clientPrenom . ' ' . $a->clientNom) ?></p>
            <div class="text-amber-500 text-sm"><?= str_repeat('★', $a->note) . str_repeat('☆', 5 - $a->note) ?></div>
        </div>
        <p class="text-xs text-gray-400 mb-3">Publie le <?= htmlspecialchars($a->dateAvis) ?></p>
        <p class="text-sm text-gray-600 italic mb-4">"<?= htmlspecialchars((string) $a->commentaire) ?>"</p>
        <div class="flex items-center justify-between pt-3 border-t border-gray-50">
            <span class="text-xs text-gray-400">ID: <?= $a->id ?></span>
            <button type="button" onclick="<?= htmlspecialchars('demanderConfirmation({titre:"Supprimer cet avis",message:"Cette opération est irréversible.",cible:' . json_encode($a->clientPrenom . ' ' . $a->clientNom) . ',actionUrl:"/avis/' . $a->id . '/delete"})', ENT_QUOTES, 'UTF-8') ?>" class="w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 text-red-500 hover:border-red-400 transition"><i class="fa-regular fa-trash-can text-xs"></i></button>
        </div>
    </div>
<?php endforeach; ?>
<?php if ($avis === []): ?><p class="col-span-2 text-center text-gray-400 py-16">Aucun avis pour le moment.</p><?php endif; ?>
</div>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>