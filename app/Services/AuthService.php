<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ClientRepositoryInterface;
use App\Interfaces\RememberTokenRepositoryInterface;
use App\Interfaces\UtilisateurRepositoryInterface;
use Exceptions\AuthException;

class AuthService
{
    public const COOKIE_NAME = 'remember_me';
    public const COOKIE_DUREE = 30 * 24 * 3600; // 30 jours

    public function __construct(
        private ClientRepositoryInterface $clients,
        private UtilisateurRepositoryInterface $utilisateurs,
        private RememberTokenRepositoryInterface $tokens,
    ) {}

    public function authentifier(string $email, string $motDePasse, bool $seSouvenir = false): array
    {
        $client = $this->clients->findByEmail($email);

        if ($client !== null) {
            if (!password_verify($motDePasse, $client->motDePasse)) {
                throw new AuthException('Mot de passe incorrect.');
            }

            return $this->connecter('CLIENT', $client->id, [
                'id' => $client->id, 'nom' => $client->nom, 'prenom' => $client->prenom,
                'email' => $client->email, 'role' => 'CLIENT',
            ], $seSouvenir);
        }

        $utilisateur = $this->utilisateurs->findByEmail($email);

        if ($utilisateur !== null) {
            if (!password_verify($motDePasse, $utilisateur->motDePasse)) {
                throw new AuthException('Mot de passe incorrect.');
            }
            if (!$utilisateur->actif) {
                throw new AuthException('Ce compte a ete desactive. Contactez un administrateur.');
            }

            return $this->connecter('UTILISATEUR', $utilisateur->id, [
                'id' => $utilisateur->id, 'nom' => $utilisateur->nom, 'prenom' => $utilisateur->prenom,
                'email' => $utilisateur->email, 'role' => $utilisateur->role,
            ], $seSouvenir);
        }

        throw new AuthException('Aucun compte associe a cet email.');
    }

    public function loginFromRememberToken(): bool
    {
        if (!empty($_SESSION['user'])) {
            return true;
        }

        $token = $_COOKIE[self::COOKIE_NAME] ?? '';
        if ($token === '') {
            return false;
        }

        $trouve = $this->tokens->findByHash(hash('sha256', $token));
        if ($trouve === null) {
            $this->oublierCookie();
            return false;
        }

        [$userType, $userId] = $trouve;

        if ($userType === 'CLIENT') {
            $client = $this->clients->findById($userId);
            if ($client === null) {
                return false;
            }
            $_SESSION['user'] = ['id' => $client->id, 'nom' => $client->nom, 'prenom' => $client->prenom,
                'email' => $client->email, 'role' => 'CLIENT'];
            return true;
        }

        $utilisateur = $this->utilisateurs->findById($userId);
        if ($utilisateur === null || !$utilisateur->actif) {
            return false;
        }
        $_SESSION['user'] = ['id' => $utilisateur->id, 'nom' => $utilisateur->nom, 'prenom' => $utilisateur->prenom,
            'email' => $utilisateur->email, 'role' => $utilisateur->role];
        return true;
    }

    public function logout(): void
    {
        $token = $_COOKIE[self::COOKIE_NAME] ?? '';
        if ($token !== '') {
            $this->tokens->deleteByHash(hash('sha256', $token));
        }
        $this->oublierCookie();
        unset($_SESSION['user']);
    }

    private function connecter(string $userType, int $userId, array $donneesSession, bool $seSouvenir): array
    {
        $_SESSION['user'] = $donneesSession;

        if ($seSouvenir) {
            $token = bin2hex(random_bytes(32));
            $this->tokens->create($userType, $userId, hash('sha256', $token),
                date('Y-m-d H:i:s', time() + self::COOKIE_DUREE));
            setcookie(self::COOKIE_NAME, $token, [
                'expires' => time() + self::COOKIE_DUREE, 'path' => '/',
                'secure' => false, 'httponly' => true, 'samesite' => 'Lax',
            ]);
        }

        return $donneesSession;
    }

    private function oublierCookie(): void
    {
        setcookie(self::COOKIE_NAME, '', ['expires' => time() - 3600, 'path' => '/']);
    }
}