<?php
/* Modelo base con PDO (consulta preparadas). Listo para la futura base de datos. */

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

abstract class Model
{
    protected PDO $db;

    protected string $table = '';

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->db->query('SELECT * FROM ' . $this->tableName())->fetchAll();
    }

    /** @return array<string, mixed>|false */
    public function find(int|string $id, string $column = 'id'): array|false
    {
        $sql = 'SELECT * FROM ' . $this->tableName() . ' WHERE ' . $this->quoteIdentifier($column) . ' = :id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    /** @param array<string, mixed> $data */
    public function insert(array $data): string|false
    {
        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $c): string => ':' . $c, $columns);
        $colsSql = implode(', ', array_map($this->quoteIdentifier(...), $columns));
        $sql = 'INSERT INTO ' . $this->tableName() . ' (' . $colsSql . ') VALUES (' . implode(', ', $placeholders) . ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    /** @param array<string, mixed> $data */
    public function update(int|string $id, array $data, string $idColumn = 'id'): int
    {
        if ($data === []) {
            return 0;
        }
        $sets = [];
        foreach (array_keys($data) as $col) {
            $sets[] = $this->quoteIdentifier((string) $col) . ' = :' . $col;
        }
        $sql = 'UPDATE ' . $this->tableName() . ' SET ' . implode(', ', $sets)
            . ' WHERE ' . $this->quoteIdentifier($idColumn) . ' = :__id';
        $data['__id'] = $id;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $stmt->rowCount();
    }

    public function delete(int|string $id, string $idColumn = 'id'): int
    {
        $sql = 'DELETE FROM ' . $this->tableName() . ' WHERE ' . $this->quoteIdentifier($idColumn) . ' = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount();
    }

    /** @param array<string, mixed> $params */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    private function tableName(): string
    {
        if ($this->table === '') {
            throw new \LogicException('Define la propiedad $table en ' . static::class);
        }

        return $this->quoteIdentifier($this->table);
    }

    private function quoteIdentifier(string $name): string
    {
        return '`' . str_replace('`', '``', $name) . '`';
    }
}
