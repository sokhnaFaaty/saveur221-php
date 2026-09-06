<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\ClientService;
use Core\View;
use Exceptions\AppException;
use Exceptions\AuthException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $auth,
         private ClientService $clientService

        ) {}

    public function showLogin(): string
    {
        return View::render('auth/connexion', ['title' => 'Connexion'], 'layouts/public');
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
    
    public function showRegister(): string
    {
        return View::render('auth/inscription', ['title' => 'Inscription'], 'layouts/public');
    }

    
    public function register(): never
    {
        try {
            $this->clientService->inscrire([
                'nom'          => $this->value('nom'),
                'prenom'       => $this->value('prenom'),
                'telephone'    => $this->value('telephone'),
                'adresse'      => $this->value('adresse'),
                'email'        => $this->value('email'),
                'mot_de_passe' => $this->value('mot_de_passe'),
            ]);
            
            flash('success', 'Compte cree avec succes, vous pouvez vous connecter.');
            View::redirect('/connexion');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
            View::redirect('/inscription');
        }
    }
}