<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\ProfilService;
use App\Services\UploadService;
use Core\View;
use Exceptions\AppException;

class ProfilController extends Controller
{
    public function __construct(
        private ProfilService $profil,
        private UploadService $uploads,
    ) {}

    public function index(): string
    {
        $profil = $this->profil->charger($_SESSION['user'] ?? []);
        $layout = ($_SESSION['user']['role'] ?? '') === 'CLIENT' ? 'layouts/public' : 'layouts/dashboard';

        return View::render('profil/index', [
            'title'  => 'Mon Profil & Securite',
            'profil' => $profil,
        ], $layout);
    }

    public function update(): never
    {
        try {
            $imageUrl = $this->uploads->upload($_FILES['photo'] ?? []);

            $this->profil->mettreAJourInfos($_SESSION['user'] ?? [], [
                'nom'       => (string) $this->value('nom', ''),
                'prenom'    => (string) $this->value('prenom', ''),
                'email'     => (string) $this->value('email', ''),
                'telephone' => (string) $this->value('telephone', ''),
                'adresse'   => (string) $this->value('adresse', ''),
            ], $imageUrl);

            flash('success', 'Profil mis a jour avec succes.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }

        View::redirect('/profil');
    }

    public function updatePassword(): never
    {
        try {
            $this->profil->changerMotDePasse(
                $_SESSION['user'] ?? [],
                (string) $this->value('ancien_mot_de_passe', ''),
                (string) $this->value('nouveau_mot_de_passe', ''),
                (string) $this->value('confirmation', ''),
            );

            flash('success', 'Mot de passe mis a jour avec succes.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }

        View::redirect('/profil');
    }
}
