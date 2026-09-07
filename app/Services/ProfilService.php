<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\UtilisateurRepositoryInterface;
use App\Models\Client;
use App\Models\Utilisateur;
use Exceptions\AppException;
use Exceptions\ValidationException;

class ProfilService
{
    public function __construct(
        private ClientRepositoryInterface $clients,
        private UtilisateurRepositoryInterface $utilisateurs,
    ) {}

    /** @return Utilisateur|Client|null */
    public function charger(array $sessionUser)
    {
        $id = (int) ($sessionUser['id'] ?? 0);
        if ($id <= 0) {
            return null;
        }

        if (($sessionUser['role'] ?? null) === 'CLIENT') {
            return $this->clients->findById($id);
        }

        return $this->utilisateurs->findById($id);
    }

    public function mettreAJourInfos(array $sessionUser, array $data, ?string $imageUrl): void
    {
        foreach (['nom', 'prenom', 'email', 'telephone'] as $champ) {
            if (!Validator::estRempli($data[$champ] ?? null)) {
                throw new ValidationException("Le champ \"$champ\" est obligatoire.");
            }
        }
        if (!Validator::estEmailValide($data['email'])) {
            throw new ValidationException("L'adresse email n'est pas valide.");
        }
        if (!Validator::estTelephoneValide($data['telephone'])) {
            throw new ValidationException('Le numero de telephone n\'est pas valide (Senegal ou Gambie).');
        }

        $id = (int) ($sessionUser['id'] ?? 0);
        $role = $sessionUser['role'] ?? '';

        if ($role === 'CLIENT') {
            $existant = $this->clients->findByEmail($data['email']);
            if ($existant !== null && $existant->id !== $id) {
                throw new AppException('Un compte existe deja avec cet email.');
            }
            $actuel = $this->clients->findById($id);
            $this->clients->update($id, [
                'nom'       => trim($data['nom']),
                'prenom'    => trim($data['prenom']),
                'telephone' => $data['telephone'],
                'adresse'   => $data['adresse'] ?? ($actuel?->adresse ?? null),
                'email'     => $data['email'],
                'image'     => $imageUrl ?? $actuel?->image,
            ]);
            $this->rafraichirSession($sessionUser, [
                'nom' => trim($data['nom']), 'prenom' => trim($data['prenom']), 'email' => $data['email'],
                'image' => $imageUrl ?? $actuel?->image,
            ]);
            return;
        }

        $existant = $this->utilisateurs->findByEmail($data['email']);
        if ($existant !== null && $existant->id !== $id) {
            throw new AppException('Un compte existe deja avec cet email.');
        }
        $actuel = $this->utilisateurs->findById($id);
        $this->utilisateurs->updateProfil(
            $id,
            trim($data['nom']),
            trim($data['prenom']),
            $data['email'],
            preg_replace('/\s+/', '', $data['telephone']),
            $imageUrl ?? $actuel?->image,
        );
        $this->rafraichirSession($sessionUser, [
            'nom' => trim($data['nom']), 'prenom' => trim($data['prenom']), 'email' => $data['email'],
            'image' => $imageUrl ?? $actuel?->image,
        ]);
    }

    public function changerMotDePasse(array $sessionUser, string $ancien, string $nouveau, string $confirmation): void
    {
        if (!Validator::estRempli($ancien) || !Validator::estRempli($nouveau)) {
            throw new ValidationException('Tous les champs du mot de passe sont obligatoires.');
        }
        if ($nouveau !== $confirmation) {
            throw new ValidationException('La confirmation ne correspond pas au nouveau mot de passe.');
        }
        if (!Validator::estMotDePasseValide($nouveau)) {
            throw new ValidationException('Le nouveau mot de passe doit contenir au moins 6 caracteres.');
        }

        $id = (int) ($sessionUser['id'] ?? 0);
        $role = $sessionUser['role'] ?? '';
        $actuel = $role === 'CLIENT' ? $this->clients->findById($id) : $this->utilisateurs->findById($id);

        if ($actuel === null || !password_verify($ancien, $actuel->motDePasse)) {
            throw new AppException("L'ancien mot de passe est incorrect.");
        }

        $hash = password_hash($nouveau, PASSWORD_DEFAULT);
        if ($role === 'CLIENT') {
            $this->clients->updateMotDePasse($id, $hash);
        } else {
            $this->utilisateurs->updateMotDePasse($id, $hash);
        }
    }

    /** @param array<string, mixed> $sessionUser @param array<string, mixed> $champs */
    private function rafraichirSession(array $sessionUser, array $champs): void
    {
        $_SESSION['user'] = array_merge($sessionUser, $champs);
    }
}
