<?php
/* Conexión PDO única. Queda lista para cuando se conecte la base de datos. */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private static ?PDO $pdo = null;

    /** @var array<string, mixed> */
    private static array $config = [];

    /** @param array<string, mixed> $config */
    public static function boot(array $config): void
    {
        self::$config = $config;
        self::$pdo = null;
    }

    public static function connection(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $driver = (string) (self::$config['driver'] ?? 'mysql');
        $host = (string) (self::$config['host'] ?? '127.0.0.1');
        $port = (int) (self::$config['port'] ?? 3306);
        $db = (string) (self::$config['database'] ?? '');
        $user = (string) (self::$config['username'] ?? '');
        $pass = (string) (self::$config['password'] ?? '');
        $charset = (string) (self::$config['charset'] ?? 'utf8mb4');

        $dsn = match ($driver) {
            'mysql' => sprintf('mysql:host=%s;port=%d;dbname=%s;charset=%s', $host, $port, $db, $charset),
            'pgsql' => sprintf('pgsql:host=%s;port=%d;dbname=%s', $host, $port, $db),
            'sqlite' => 'sqlite:' . $db,
            default => throw new \InvalidArgumentException("Driver PDO no soportado: {$driver}"),
        };

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new PDOException('Error de conexión a la base de datos: ' . $e->getMessage(), (int) $e->getCode(), $e);
        }

        return self::$pdo;
    }
}
