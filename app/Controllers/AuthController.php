<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use Core\View;
use Exceptions\AuthException;

class AuthController extends Controller
{
    public function __construct(private AuthService $auth) {}

    public function showLogin(): string
    {
        return View::render('auth/connexion', ['title' => 'Connexion'], null);
    }

    public function login(): never
    {
        $email = trim((string) $this->value('email', ''));
        $motDePasse = (string) $this->value('mot_de_passe', '');
        $seSouvenir = (bool) $this->value('se_souvenir', false);

        try {
            $this->auth->authentifier($email, $motDePasse, $seSouvenir);
            flash('success', 'Connexion reussie.');
            View::redirect('/');
        } catch (AuthException $e) {
            flash('error', $e->getMessage());
            View::redirect('/connexion');
        }
    }

    public function logout(): never
    {
        $this->auth->logout();
        flash('success', 'Vous etes deconnecte.');
        View::redirect('/connexion');
    }
}