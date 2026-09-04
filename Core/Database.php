<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function connect(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                $_ENV['DB_HOST'] ?? '127.0.0.1',
                $_ENV['DB_PORT'] ?? '5432',
                $_ENV['DB_NAME'] ?? 'saveur221'
            );

            try {
                self::$pdo = new PDO($dsn, $_ENV['DB_USER'] ?? 'postgres', $_ENV['DB_PASSWORD'] ?? '', [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, 
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new PDOException('Echec de connexion a la base : ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }

    // Pour faire les requetes SELECT (renvoie un tableau d'objets)
    public static function executeSelect(string $sql, array $params = []): array
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Pour faire les requetes INSERT, UPDATE, DELETE (renvoie true ou false)
    public static function executeUpdate(string $sql, array $params = []): bool
    {
        $stmt = self::connect()->prepare($sql);
        return $stmt->execute($params);
    }
}