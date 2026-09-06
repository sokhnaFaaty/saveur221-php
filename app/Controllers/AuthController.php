<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\AuthService;
use App\Services\ClientService;
use App\Services\UploadService;
use Core\View;
use Exceptions\AppException;
use Exceptions\AuthException;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $auth,
        private ClientService $clientService,
        private UploadService $uploads,
    ) {}

    public function showLogin(): string
    {
        return View::render('auth/connexion', ['title' => 'Connexion'], 'layouts/auth');
    }

    public function login(): never
    {
        $identifiant = trim((string) $this->value('email', ''));
        $motDePasse = (string) $this->value('mot_de_passe', '');
        $seSouvenir = (bool) $this->value('se_souvenir', false);

        try {
            $this->auth->authentifier($identifiant, $motDePasse, $seSouvenir);
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
        return View::render('auth/inscription', ['title' => 'Inscription'], 'layouts/auth');
    }

    public function register(): never
    {
        $motDePasse = (string) $this->value('mot_de_passe', '');
        $confirmation = (string) $this->value('confirmation', '');

        if ($motDePasse !== $confirmation) {
            flash('error', 'Les mots de passe ne correspondent pas.');
            View::redirect('/inscription');
        }

        try {
            $image = $this->uploads->upload($_FILES['image'] ?? []);

            $this->clientService->inscrire([
                'nom'          => $this->decouperNomComplet((string) $this->value('nom_complet', ''))[1],
                'prenom'       => $this->decouperNomComplet((string) $this->value('nom_complet', ''))[0],
                'telephone'    => preg_replace('/\s+/', '', (string) $this->value('telephone', '')),
                'adresse'      => (string) $this->value('quartier_de_livraison', ''),
                'email'        => $this->value('email'),
                'mot_de_passe' => $motDePasse,
                'image'        => $image,
            ]);

            flash('success', 'Compte cree avec succes, vous pouvez vous connecter.');
            View::redirect('/connexion');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
            View::redirect('/inscription');
        }
    }

    /** @return array{0: string, 1: string} prenom, nom */
    private function decouperNomComplet(string $nomComplet): array
    {
        $parties = preg_split('/\s+/', $nomComplet, 2);
        $prenom = (string) ($parties[0] ?? '');
        $nom = trim((string) ($parties[1] ?? ''));
        return [$prenom, $nom];
    }
}