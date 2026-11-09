<?php
/* Anuncios publicados en el Inicio, guardados en MySQL. */

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Anuncio extends Model
{
    protected string $table = 'anuncios';

    /** @return list<array<string, mixed>> */
    public static function todos(int $limite = 50): array
    {
        $limite = max(1, $limite);
        $filas = (new self())->query(
            "SELECT * FROM `anuncios` ORDER BY `publicado_en` DESC, `id` DESC LIMIT {$limite}"
        )->fetchAll();

        foreach ($filas as &$fila) {
            $fila['fecha'] = fecha_es((string) $fila['publicado_en']);
        }
        unset($fila);

        return $filas;
    }

    public static function buscar(int $id): array|false
    {
        return (new self())->find($id);
    }

    /** @param array<string, mixed> $datos */
    public static function crear(array $datos): int
    {
        return (int) (new self())->insert([
            'categoria' => (string) $datos['categoria'],
            'etiqueta' => (string) $datos['etiqueta'],
            'titulo' => (string) $datos['titulo'],
            'texto' => (string) $datos['texto'],
            'autor' => (string) $datos['autor'],
            'publicado_en' => (string) $datos['publicado_en'],
        ]);
    }

    public static function eliminar(int $id): bool
    {
        return (new self())->delete($id) > 0;
    }
}
