<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    /** @return array<string, mixed> */
    protected function input(): array
    {
        if (!empty($_POST)) {
        return $_POST;
    }
    $corps = json_decode(file_get_contents('php://input') ?: '', true);
    return is_array($corps) ? $corps : [];

    }

    protected function value(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    // Retourne une valeur numerique, ou $defaut si absente/vide/non numerique (jamais de required)
    protected function valeurNumerique(string $key, mixed $defaut = 0): mixed
    {
        $v = $this->value($key);
        return (is_numeric($v) && $v !== '') ? $v : $defaut;
    }
    
}
