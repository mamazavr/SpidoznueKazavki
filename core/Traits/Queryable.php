<?php

namespace Core\Traits;

use Enums\SQL;
use PDO;

trait Queryable
{
    private array $commands = [];
    private static string $query = '';

    static protected string|null $tableName = null;

    static public function select(array $columns = ['*']): static
    {
        static::resetQuery();
        static::$query = "SELECT " . implode(', ', $columns) . " FROM " . static::$tableName . " ";
        $obj = new static;
        $obj->commands[] = 'select';
        return $obj;
    }

    static public function all(): array
    {
        return static::select()->get();
    }

    static public function find(int $id): static|false
    {
        $query = db()->prepare("SELECT * FROM " . static::$tableName . " WHERE id = :id");
        $query->bindParam('id', $id);
        $query->execute();
        return $query->fetchObject(static::class);
    }

    // ... (rest of the methods remain unchanged)

    protected function prevent(array $allowedMethods): bool
    {
        foreach ($allowedMethods as $method) {
            if (in_array($method, $this->commands)) {
                return true;
            }
        }
        return false;
    }

    private static function resetQuery(): void
    {
        static::$query = '';
    }
}
