<?php
/** @var int $page @var int $totalPages @var string $pageVar */
if (($totalPages ?? 1) <= 1) return;
$pageVar = $pageVar ?? 'page';
?>
<div class="flex items-center justify-center gap-2 mt-6">
    <?php $ancre = $anchor ?? null; ?>
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?<?= http_build_query(array_merge($_GET, [$pageVar => $i])) ?><?= $ancre ? '#' . $ancre : '' ?>"
       class="w-9 h-9 flex items-center justify-center rounded-lg text-sm font-semibold <?= $i === $page ? 'bg-gray-900 text-white' : 'bg-white border border-gray-200 hover:border-primary' ?>">
        <?= $i ?>
    </a>
    <?php endfor; ?>
    <?php if ($page < $totalPages): ?>
    <a href="?<?= http_build_query(array_merge($_GET, [$pageVar => $page + 1])) ?><?= $ancre ? '#' . $ancre : '' ?>" class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-gray-200 hover:border-primary">
        <i class="fa-solid fa-chevron-right text-xs"></i>
    </a>
    <?php endif; ?>
</div>