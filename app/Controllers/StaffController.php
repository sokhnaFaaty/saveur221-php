<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\UtilisateurService;
use App\Services\UploadService;
use Core\View;
use Exceptions\AppException;

class StaffController extends Controller
{
    public function __construct(
        private UtilisateurService $utilisateurService,
        private UploadService $uploads,
    ) {}

    public function index(): string
    {
        $pagination = paginer($this->utilisateurService->listerUtilisateurs(), (int) $this->value('page', 1));
        return View::render('staff/index', [
            'title' => 'Gestion des Utilisateurs Staff',
            'staff' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
            'vue' => $this->value('vue', 'tableau'),
        ], 'layouts/dashboard');
    }

    public function store(): never
    {
        try {
            $imageUrl = null;
            $fichier = $_FILES['image_file'] ?? [];
            $lien = trim((string) $this->value('image_url', ''));
            if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                $imageUrl = $this->uploads->upload($fichier);
            } elseif ($lien !== '') {
                $imageUrl = $lien;
            }

            $this->utilisateurService->ajouterUtilisateur([
                'nom' => $this->value('nom'), 'prenom' => $this->value('prenom'),
                'email' => $this->value('email'), 'telephone' => $this->value('telephone'),
                'adresse' => $this->value('adresse'),
                'role' => $this->value('role'), 'mot_de_passe' => $this->value('mot_de_passe'),
                'actif' => $this->value('actif', '1'), 'image' => $imageUrl,
            ]);
            flash('success', 'Compte staff cree.');
        } catch (AppException $e) {
            flash('error', $e->getMessage());
        }
        View::redirect('/staff');
    }

    public function toggle(int $id): never
    {
        $actif = $this->value('actif') === '1' || $this->value('actif', '0') === true;
        $this->utilisateurService->activerDesactiver($id, $actif);
        flash('success', $actif ? 'Compte active.' : 'Compte desactive.');
        View::redirect('/staff');
    }

    public function delete(int $id): never
    {
        $this->utilisateurService->supprimerUtilisateur($id);
        flash('success', 'Compte supprime.');
        View::redirect('/staff');
    }

    public function corbeille(): string
    {
        $pagination = paginer($this->utilisateurService->listerUtilisateursSupprimes(), (int) $this->value('page', 1));
        return View::render('staff/corbeille', [
            'title' => 'Corbeille du staff',
            'staff' => $pagination['items'],
            'page' => $pagination['page'],
            'totalPages' => $pagination['totalPages'],
        ], 'layouts/dashboard');
    }

    public function restaurer(int $id): never
    {
        $this->utilisateurService->restaurerUtilisateur($id);
        flash('success', 'Compte restaure.');
        View::redirect('/staff/corbeille');
    }

    public function supprimerDefinitivement(int $id): never
    {
        $this->utilisateurService->supprimerUtilisateurDefinitivement($id);
        flash('success', 'Compte supprime definitivement.');
        View::redirect('/staff/corbeille');
    }
}