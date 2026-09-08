<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\UtilisateurRepositoryInterface;
use App\Models\Utilisateur;
use Exceptions\EmailDejaUtiliseException;
use Exceptions\TelephoneDejaUtiliseException;
use Exceptions\UtilisateurInexistantException;
use Exceptions\ValidationException;

class UtilisateurService
{
    public function __construct(private UtilisateurRepositoryInterface $utilisateurs) {}

    public function ajouterUtilisateur(array $data): Utilisateur
    {
        foreach (['nom', 'prenom', 'telephone', 'email', 'mot_de_passe', 'role'] as $champ) {
            if (!Validator::estRempli($data[$champ] ?? null)) {
                throw new ValidationException("Le champ \"$champ\" est obligatoire.");
            }
        }
        if (!Validator::estEmailValide($data['email'])) {
            throw new ValidationException("L'adresse email n'est pas valide.");
        }
        if (!Validator::estTelephoneValide($data['telephone'])) {
            throw new ValidationException('Le numero de telephone n\'est pas valide.');
        }
        if (!Validator::estMotDePasseValide($data['mot_de_passe'])) {
            throw new ValidationException('Le mot de passe doit contenir au moins 6 caracteres.');
        }
        if (!in_array($data['role'], ['ADMIN', 'GERANT'], true)) {
            throw new ValidationException('Role invalide.');
        }
        if ($this->utilisateurs->findByEmail($data['email']) !== null) {
            throw new EmailDejaUtiliseException('Un compte existe deja avec cet email.');
        }

        return $this->utilisateurs->create([
            'nom' => trim($data['nom']), 'prenom' => trim($data['prenom']),
            'email' => $data['email'], 'telephone' => $data['telephone'], 'role' => $data['role'],
            'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
        ]);
    }

    public function listerUtilisateurs(): array
    {
        return $this->utilisateurs->findAll();
    }

    public function activerDesactiver(int $id, bool $actif): void
    {
        if ($this->utilisateurs->findById($id) === null) {
            throw new UtilisateurInexistantException("Aucun utilisateur trouve avec l'id $id");
        }
        $this->utilisateurs->updateStatut($id, $actif);
    }

    public function supprimerUtilisateur(int $id): void
    {
        $this->utilisateurs->delete($id);
    }

    public function listerUtilisateursSupprimes(): array
    {
        return $this->utilisateurs->findDeleted();
    }

    public function restaurerUtilisateur(int $id): void
    {
        $this->utilisateurs->restore($id);
    }

    public function supprimerUtilisateurDefinitivement(int $id): void
    {
        $this->utilisateurs->forceDelete($id);
    }
}