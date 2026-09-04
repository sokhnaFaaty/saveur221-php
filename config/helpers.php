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