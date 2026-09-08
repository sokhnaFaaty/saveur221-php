<h1 class="text-2xl font-extrabold mb-6 mt-8">Nos Categories</h1>
<div class="grid grid-cols-2 md:grid-cols-5 gap-5 mb-16">
    <?php foreach ($categories as $categorie): ?>
    <a href="/catalogue?categorie=<?= $categorie->id ?>" class="group rounded-xl overflow-hidden border border-gray-100 hover:shadow-xl hover:-translate-y-1 transition duration-300 bg-white">
        <?php if ($categorie->image): ?>
            <div class="h-28 overflow-hidden">
                <img src="<?= htmlspecialchars($categorie->image) ?>" alt="<?= htmlspecialchars($categorie->libelle) ?>"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            </div>
        <?php else: ?>
            <div class="h-28 bg-primary-light flex items-center justify-center text-primary text-2xl">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        <?php endif; ?>
        <div class="p-3">
            <h3 class="font-semibold text-sm"><?= htmlspecialchars($categorie->libelle) ?></h3>
            <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= htmlspecialchars((string) $categorie->description) ?></p>
        </div>
    </a>
    <?php endforeach; ?>
</div>