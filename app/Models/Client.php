<?php

declare(strict_types=1);

namespace App\Models;

class Client
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $prenom,
        public readonly string $telephone,
        public readonly ?string $adresse,
        public readonly string $email,
        public readonly string $motDePasse,
        public readonly ?string $image,
    ) {}

    public static function fromRow(object $row): self
    {
        return new self(
            id: (int) $row->id,
            nom: $row->nom,
            prenom: $row->prenom,
            telephone: $row->telephone,
            adresse: $row->adresse,
            email: $row->email,
            motDePasse: $row->mot_de_passe,
            image: $row->image,
        );
    }

    public function nomComplet(): string
    {
        return trim($this->prenom . ' ' . $this->nom);
    }
}