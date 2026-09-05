<?php

declare(strict_types=1);

namespace App\Controllers;

abstract class Controller
{
    /** @return array<string, mixed> */
    protected function input(): array
    {
        return $_POST;
    }

    protected function value(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }
    protected function input(): array
{
    if (!empty($_POST)) {
        return $_POST;
    }
    $corps = json_decode(file_get_contents('php://input') ?: '', true);
    return is_array($corps) ? $corps : [];
}
}
