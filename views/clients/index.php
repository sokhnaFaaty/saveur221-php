<h1 class="text-2xl font-extrabold mb-1">Répertoire des Clients (Consultation)</h1>
<p class="text-sm text-gray-500 mb-6">Consultation en lecture seule des profils clients enregistrés et de leurs historiques de commandes.</p>

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
            <tr><th class="px-4 py-3">Client</th><th class="px-4 py-3">Téléphone</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Adresse</th><th class="px-4 py-3">Commandes</th></tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
        <?php foreach ($clients as $client): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold text-xs"><?= mb_substr($client->prenom, 0, 1) ?></span>
                    <span class="font-semibold"><?= htmlspecialchars($client->prenom . ' ' . $client->nom) ?></span>
                </td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($client->telephone) ?></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars($client->email) ?></td>
                <td class="px-4 py-3 text-gray-500"><?= htmlspecialchars((string) $client->adresse) ?></td>
                <td class="px-4 py-3">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-primary-light text-primary"><?= $nbCommandesParClient[$client->id] ?? 0 ?> commande(s)</span>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($clients === []): ?><tr><td colspan="5" class="text-center text-gray-400 py-10">Aucun client.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    <?php foreach ($clients as $client): ?>
    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-md transition">
        <div class="flex items-center gap-3 mb-3">
            <span class="w-10 h-10 rounded-full bg-primary-light text-primary flex items-center justify-center font-bold"><?= mb_substr($client->prenom, 0, 1) ?></span>
            <div>
                <p class="font-bold"><?= htmlspecialchars($client->prenom . ' ' . $client->nom) ?></p>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-primary-light text-primary"><?= $nbCommandesParClient[$client->id] ?? 0 ?> commande(s)</span>
            </div>
        </div>
        <div class="space-y-1 text-sm text-gray-500">
            <p><i class="fa-solid fa-phone w-4 text-primary"></i> <?= htmlspecialchars($client->telephone) ?></p>
            <p><i class="fa-solid fa-envelope w-4 text-primary"></i> <?= htmlspecialchars($client->email) ?></p>
            <p><i class="fa-solid fa-location-dot w-4 text-primary"></i> <?= htmlspecialchars((string) $client->adresse) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if ($clients === []): ?><p class="col-span-full text-center text-gray-400 py-16">Aucun client.</p><?php endif; ?>
</div>
<?php endif; ?>

<?php include VIEW_PATH . '/partials/pagination.php'; ?>