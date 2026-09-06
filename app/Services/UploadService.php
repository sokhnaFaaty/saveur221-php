<?php

declare(strict_types=1);

namespace App\Services;

use Exceptions\ValidationException;

class UploadService
{
    private const MAX_SIZE = 2 * 1024 * 1024; // 2 Mo

    private const TYPES = [
        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png', 'webp' => 'image/webp',
    ];

    private array $cloudinary;
    private string $uploadDir;

    public function __construct()
    {
        $this->cloudinary = require dirname(__DIR__, 2) . '/config/cloudinary.php';
        $this->uploadDir = dirname(__DIR__, 2) . '/public/assets/img/produits';
    }

    public function isCloudinaryConfigured(): bool
    {
        return !empty($this->cloudinary['enabled'])
            && $this->cloudinary['cloud_name'] !== ''
            && $this->cloudinary['api_key'] !== ''
            && $this->cloudinary['api_secret'] !== '';
    }

    /**
     * @param array<string, mixed> $file Element $_FILES['image']
     * @return string|null Url de l'image, ou null si aucun fichier envoye
     */
    public function upload(array $file): ?string
    {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $this->valider($file);

        return $this->isCloudinaryConfigured()
            ? $this->uploadVersCloudinary((string) $file['tmp_name'])
            : $this->uploadLocalement((string) $file['tmp_name']);
    }

    private function valider(array $file): void
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException("L'envoi de l'image a echoue.");
        }
        if ((int) $file['size'] > self::MAX_SIZE) {
            throw new ValidationException("L'image est trop volumineuse (2 Mo maximum).");
        }

        $mime = $this->detecterMime((string) $file['tmp_name']);
        if (!in_array($mime, self::TYPES, true)) {
            throw new ValidationException('Le fichier doit etre une image (JPG, PNG ou WebP).');
        }
    }

    private function detecterMime(string $tmpName): string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = $finfo === false ? '' : (finfo_file($finfo, $tmpName) ?: '');
        if ($finfo !== false) {
            finfo_close($finfo);
        }
        return $mime;
    }

    private function uploadLocalement(string $tmpName): string
    {
        $extension = array_search($this->detecterMime($tmpName), self::TYPES, true) ?: 'jpg';
        $nomFichier = bin2hex(random_bytes(16)) . '.' . $extension;

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }
        if (!move_uploaded_file($tmpName, $this->uploadDir . '/' . $nomFichier)) {
            throw new ValidationException("Impossible d'enregistrer l'image sur le serveur.");
        }

        return '/assets/img/produits/' . $nomFichier;
    }

    private function uploadVersCloudinary(string $tmpName): string
    {
        $timestamp = time();
        $signature = sha1('timestamp=' . $timestamp . $this->cloudinary['api_secret']);

        $ch = curl_init('https://api.cloudinary.com/v1_1/' . $this->cloudinary['cloud_name'] . '/image/upload');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => [
                'file'      => new \CURLFile($tmpName),
                'api_key'   => (string) $this->cloudinary['api_key'],
                'timestamp' => (string) $timestamp,
                'signature' => $signature,
                'folder'    => 'saveur221/produits',
            ],
        ]);

        $reponse = curl_exec($ch);
        $erreur = curl_error($ch);
        curl_close($ch);

        if ($erreur !== '' || !is_string($reponse)) {
            throw new ValidationException('Echec de la communication avec Cloudinary.');
        }

        $donnees = json_decode($reponse, true);
        if (!is_array($donnees) || empty($donnees['secure_url'])) {
            throw new ValidationException("Cloudinary a refuse l'image.");
        }

        return (string) $donnees['secure_url'];
    }
}