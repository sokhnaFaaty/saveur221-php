<?php /** @var \App\Models\Produit|null $produit */ /** @var \App\Models\Categorie[] $categories */ ?>
<a href="/produits" class="text-sm text-gray-500 hover:text-primary transition mb-4 inline-flex items-center gap-2"><i class="fa-solid fa-arrow-left"></i> Retour</a>
<div class="bg-white rounded-xl p-6 shadow-sm max-w-2xl">
    <h1 class="text-xl font-extrabold mb-5"><?= $produit ? 'Modifier un plat' : 'Ajouter un plat' ?></h1>
    <form method="post" action="<?= $produit ? '/produits/' . $produit->id . '/update' : '/produits' ?>" enctype="multipart/form-data" class="space-y-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Nom du plat</label>
            <input type="text" name="libelle" value="<?= htmlspecialchars(ancienneValeur('libelle', (string) ($produit->libelle ?? ''))) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm <?= erreurChamp('libelle') !== '' ? 'border-red-500' : '' ?>">
            <?php if ($erreur = erreurChamp('libelle')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Categorie</label>
                <select name="categorie_id" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm bg-white <?= erreurChamp('categorie_id') !== '' ? 'border-red-500' : '' ?>">
                    <?php foreach ($categories as $c): ?>
                    <option value="<?= $c->id ?>" <?= (ancienneValeur('categorie_id', '') === (string) $c->id || (ancienneValeur('categorie_id', '') === '' && $produit && $produit->categorieId === $c->id)) ? 'selected' : '' ?>><?= htmlspecialchars($c->libelle) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if ($erreur = erreurChamp('categorie_id')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Prix (FCFA)</label>
                <input type="text" name="prix" value="<?= htmlspecialchars(ancienneValeur('prix', (string) ($produit->prix ?? ''))) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm <?= erreurChamp('prix') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('prix')): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div><label class="block text-sm font-semibold mb-1">Stock initial</label>
                <input type="text" name="quantite_stock" value="<?= htmlspecialchars(ancienneValeur('quantite_stock', (string) ($produit->quantiteStock ?? ''))) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm <?= erreurChamp('quantite_stock') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('quantite_stock')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
            <div><label class="block text-sm font-semibold mb-1">Seuil alerte</label>
                <input type="text" name="seuil_alerte" value="<?= htmlspecialchars(ancienneValeur('seuil_alerte', (string) ($produit->seuilAlerte ?? '5'))) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm <?= erreurChamp('seuil_alerte') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('seuil_alerte')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
            <div><label class="block text-sm font-semibold mb-1">Temps prep (min)</label>
                <input type="text" name="temps_preparation" value="<?= htmlspecialchars(ancienneValeur('temps_preparation', (string) ($produit->tempsPreparation ?? ''))) ?>" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm <?= erreurChamp('temps_preparation') !== '' ? 'border-red-500' : '' ?>">
                <?php if ($erreur = erreurChamp('temps_preparation')): ?><p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p><?php endif; ?>
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Description detaillee</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm <?= erreurChamp('description') !== '' ? 'border-red-500' : '' ?>"><?= htmlspecialchars(ancienneValeur('description', (string) ($produit->description ?? ''))) ?></textarea>
            <?php if ($erreur = erreurChamp('description')): ?>
                <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($erreur) ?></p>
            <?php endif; ?>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-2">Image <span class="text-xs font-normal text-gray-400">(choisissez une option)</span></label>
            <div class="space-y-3">
                <div class="flex items-center gap-2">
                    <input type="radio" name="image_option" value="file" id="opt_file" checked class="accent-red-600">
                    <label for="opt_file" class="text-sm font-semibold">Option A : Téléverser un fichier</label>
                </div>
                <div class="pl-6" id="bloc-file">
                    <input type="file" name="image_file" accept="image/png,image/jpeg,image/webp" class="text-sm">
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="radio" name="image_option" value="url" id="opt_url" class="accent-red-600">
                    <label for="opt_url" class="text-sm font-semibold">Option B : Lien internet</label>
                </div>
                <div class="pl-6 hidden" id="bloc-url">
                    <input type="text" name="image_url" value="<?= htmlspecialchars((string) ($produit->image ?? '')) ?>"
                           placeholder="https://example.com/image.jpg"
                           class="w-full px-4 py-2.5 rounded-lg border border-gray-200 text-sm placeholder-gray-400">
                </div>
            </div>
            <?php if ($produit && $produit->image): ?><p class="text-xs text-gray-400 mt-2">Laisser vide pour conserver l'image actuelle.</p><?php endif; ?>
        </div>
        <button type="submit" class="w-full py-2.5 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary-dark transition">Enregistrer</button>
    </form>
</div>
<?php effacerErreursFormulaire(); ?>
<script>
    document.querySelectorAll('input[name="image_option"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const utiliserFichier = document.getElementById('opt_file').checked;
            document.getElementById('bloc-file').classList.toggle('hidden', !utiliserFichier);
            document.getElementById('bloc-url').classList.toggle('hidden', utiliserFichier);
            if (utiliserFichier) {
                document.querySelector('input[name="image_url"]').value = '';
            } else {
                document.querySelector('input[name="image_file"]').value = '';
            }
        });
    });
</script>