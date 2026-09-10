<?php

declare(strict_types=1);

namespace App\Middleware;

use Core\View;

class Middleware
{
    public static function auth(): void
    {
        if (!isConnected()) {
            View::redirect('/connexion');
        }
    }

    public static function guest(): void
    {
        if (isConnected()) {
            View::redirect('/');
        }
    }

    public static function role(string ...$roles): void
    {
        self::auth();

        foreach ($roles as $role) {
            if (hasRole($role)) {
                return;
            }
        }

        http_response_code(403);
        echo View::render('errors/403', ['title' => 'Acces refuse'], null);
        exit;
    }
}