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

//Stocke les erreurs de validation par champ (affichées sous chaque input)
function flashErreurs(array $erreurs, array $anciennes = []): void
{
    $_SESSION['erreurs'] = $erreurs;
    if ($anciennes !== []) {
        $_SESSION['anciennes'] = $anciennes;
    }
}

//Retourne le message d'erreur du champ donné, ou '' si aucun. 
function erreurChamp(string $champ): string
{
    return (string) ($_SESSION['erreurs'][$champ] ?? '');
}

//Retourne la valeur saisie précédemment pour le champ donné. 
function ancienneValeur(string $champ, string $defaut = ''): string
{
    return (string) ($_SESSION['anciennes'][$champ] ?? $defaut);
}

//face les erreurs/anciennes valeurs après affichage du formulaire. 
function effacerErreursFormulaire(): void
{
    unset($_SESSION['erreurs'], $_SESSION['anciennes']);
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