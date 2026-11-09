<?php
/* Bitácora: historial de acciones del panel. */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Bitacora extends Model
{
    protected string $table = 'bitacora';

    public static function registrar(?int $usuarioId, string $usuario, string $accion, string $detalle): void
    {
        (new self())->insert([
            'usuario_id' => $usuarioId,
            'usuario' => $usuario,
            'accion' => $accion,
            'detalle' => $detalle,
        ]);
    }

    /** @return list<array<string, mixed>> */
    public static function recientes(int $limite = 20): array
    {
        $limite = max(1, $limite);
        $filas = (new self())->query(
            "SELECT * FROM `bitacora` ORDER BY `creado_en` DESC, `id` DESC LIMIT {$limite}"
        )->fetchAll();

        foreach ($filas as &$fila) {
            $fila['fecha'] = fecha_hora_es((string) $fila['creado_en']);
        }
        unset($fila);

        return $filas;
    }
}
