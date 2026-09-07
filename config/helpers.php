<?php
function isConnected() {
    return isset($_SESSION["user"]);
}

function hasRole(string $role) {
    if (!isConnected() || !isset($_SESSION["user"]["role"])) {
        return false;
    }
    return $_SESSION["user"]["role"] == $role;
}
function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/** @return array{items: array, page: int, totalPages: int, total: int} */
function paginer(array $items, int $page, int $parPage = 6): array
{
    $total = count($items);
    $totalPages = max(1, (int) ceil($total / $parPage));
    $page = max(1, min($page, $totalPages));
    $debut = ($page - 1) * $parPage;

    return ['items' => array_slice($items, $debut, $parPage), 'page' => $page, 'totalPages' => $totalPages, 'total' => $total];
}