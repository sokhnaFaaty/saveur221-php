<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ClientRepositoryInterface;
use App\Models\Client;
use Exceptions\EmailDejaUtiliseException;
use Exceptions\TelephoneDejaUtiliseException;
use Exceptions\ValidationException;

class ClientService
{
    public function __construct(private ClientRepositoryInterface $clients) {}

    public function listerClients(): array
{
    return $this->clients->findAll();
}
    public function inscrire(array $data): Client
    {
        foreach (['nom', 'prenom', 'telephone', 'email', 'mot_de_passe'] as $champ) {
            if (!Validator::estRempli($data[$champ] ?? null)) {
                throw new ValidationException("Le champ \"$champ\" est obligatoire.", $champ);
            }
        }

        if (!Validator::estEmailValide($data['email'])) {
            throw new ValidationException("L'adresse email n'est pas valide.", 'email');
        }
        if (!Validator::estTelephoneValide($data['telephone'])) {
            throw new ValidationException('Le numero de telephone n\'est pas valide (Senegal ou Gambie).', 'telephone');
        }
        if (!Validator::estMotDePasseValide($data['mot_de_passe'])) {
            throw new ValidationException('Le mot de passe doit contenir au moins 6 caracteres.', 'mot_de_passe');
        }

        if ($this->clients->findByEmail($data['email']) !== null) {
            throw new EmailDejaUtiliseException('Un compte existe deja avec cet email.', 'email');
        }
        if ($this->clients->findByTelephone($data['telephone']) !== null) {
            throw new TelephoneDejaUtiliseException('Un compte existe deja avec ce numero de telephone.', 'telephone');
        }

        return $this->clients->create([
            'nom'          => trim($data['nom']),
            'prenom'       => trim($data['prenom']),
            'telephone'    => $data['telephone'],
            'adresse'      => $data['adresse'] ?? null,
            'email'        => $data['email'],
            'mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
            'image'        => $data['image'] ?? null,
        ]);
    }
}