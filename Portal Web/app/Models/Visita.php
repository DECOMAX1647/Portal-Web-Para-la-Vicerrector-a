<?php
/* Contador de personas que han visitado el portal público. */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Visita extends Model
{
    protected string $table = 'visitas';

    /* Suma una visita por navegador, usando una cookie de un año. */
    public static function registrar(): void
    {
        $token = (string) ($_COOKIE['uptp_visitante'] ?? '');
        $valido = preg_match('/^[a-f0-9]{32}$/', $token) === 1;

        if ($valido && self::existe($token)) {
            return;
        }

        if (!$valido) {
            $token = bin2hex(random_bytes(16));
        }

        setcookie('uptp_visitante', $token, [
            'expires' => time() + 31536000,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        $_COOKIE['uptp_visitante'] = $token;

        (new self())->query(
            'INSERT IGNORE INTO `visitas` (`token`) VALUES (:token)',
            ['token' => $token]
        );
    }

    public static function total(): int
    {
        return (int) (new self())->query('SELECT COUNT(*) FROM `visitas`')->fetchColumn();
    }

    private static function existe(string $token): bool
    {
        return (bool) (new self())->query(
            'SELECT 1 FROM `visitas` WHERE `token` = :token LIMIT 1',
            ['token' => $token]
        )->fetchColumn();
    }
}
