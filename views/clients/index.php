<h1 class="text-2xl font-extrabold mb-1">Repertoire des Clients (Consultation)</h1>
<p class="text-sm text-gray-500 mb-6">Consultation en lecture seule des profils clients enregistres et de leurs historiques de commandes.</p>

<div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
            <tr><th class="px-4 py-3">Client</th><th class="px-4 py-3">Telephone</th><th class="px-4 py-3">Email</th><th class="px-4 py-3">Adresse</th><th class="px-4 py-3">Commandes</th></tr>
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
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-primary-light text-primary"><?= $nbCommandesParClient[$client->id] ?> commande(s)</span>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if ($clients === []): ?><tr><td colspan="5" class="text-center text-gray-400 py-10">Aucun client.</td></tr><?php endif; ?>
        </tbody>
    </table>
</div>