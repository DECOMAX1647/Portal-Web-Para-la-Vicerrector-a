<?php
/* Usuario administrativo: consulta y validación contra la base de datos. */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Usuario extends Model
{
    protected string $table = 'usuarios';

    /** Devuelve los datos del usuario si la contraseña es correcta. */
    public static function validar(string $usuario, string $clave): array|false
    {
        if ($usuario === '' || $clave === '') {
            return false;
        }

        $fila = (new self())->query(
            'SELECT * FROM `usuarios` WHERE `usuario` = :usuario AND `activo` = 1 LIMIT 1',
            ['usuario' => $usuario]
        )->fetch();

        if (!$fila || !password_verify($clave, (string) $fila['clave_hash'])) {
            return false;
        }

        return $fila;
    }

    public static function buscar(int $id): array|false
    {
        return (new self())->find($id);
    }
}
