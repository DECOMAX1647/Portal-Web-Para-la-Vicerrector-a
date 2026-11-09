<?php
/* Documentos del repositorio guardados en MySQL. */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Documento extends Model
{
    protected string $table = 'documentos';

    /** @return list<array<string, mixed>> */
    public static function todos(): array
    {
        return (new self())->query(
            'SELECT * FROM `documentos` ORDER BY `creado_en` DESC, `id` DESC'
        )->fetchAll();
    }

    public static function buscar(int $id): array|false
    {
        return (new self())->find($id);
    }

    /** @param array<string, mixed> $datos */
    public static function crear(array $datos): int
    {
        return (int) (new self())->insert([
            'titulo' => (string) $datos['titulo'],
            'autor' => (string) $datos['autor'],
            'categoria' => (string) $datos['categoria'],
            'anio' => (int) $datos['anio'],
            'descripcion' => (string) $datos['descripcion'],
            'archivo' => (string) $datos['archivo'],
        ]);
    }

    public static function eliminar(int $id): bool
    {
        return (new self())->delete($id) > 0;
    }
}
