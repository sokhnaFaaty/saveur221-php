<?php

declare(strict_types=1);

namespace App\Services;

class Validator
{
    
    private const REGEX_TELEPHONE = '/^(\+221)?7[0-9]{8}$|^(\+220)?[23679][0-9]{6}$/';

    public static function estRempli(?string $valeur): bool
    {
        return $valeur !== null && trim($valeur) !== '';
    }

    public static function estEmailValide(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function estTelephoneValide(string $telephone): bool
    {
        return preg_match(self::REGEX_TELEPHONE, $telephone) === 1;
    }

    public static function estMotDePasseValide(string $motDePasse): bool
    {
        return strlen($motDePasse) >= 8;
    }
    
    public static function estNumerique(mixed $valeur): bool
{
    return is_numeric($valeur);
}
}