<?php

namespace Core\Traits;

use PDO;

trait Queryable
{
    protected static string|null $tableName = null;
    protected static string $query = '';
    protected array $commands = [];
    protected array $select = [];
    protected string $from = '';
    protected array $where = [];
    protected string $orderBy = '';
    protected string $limit = '';

    /**
     * @param array $columns (e.g. ['name', 'surname'], ['users.name as u_name']) => SELECT name, surname ....
     * @return static
     */
    public function select(array $columns = ['*']): static
    {
        $this->resetQuery();
        $this->select = $columns;
        $this->from = static::$tableName;
        $this->query = "SELECT " . implode(', ', $this->select) . " FROM " . $this->from . " ";

        $this->commands[] = 'select';

        return $this;
    }

    public function all(): array
    {
        return $this->select()->get();
    }

    public function find(int $id): static|false
    {
        $this->where = ['id' => $id];
        return $this->select()->getOne();
    }

    public function findBy(string $column, $value): static|false
    {
        $this->where = [$column => $value];
        return $this->select()->getOne();
    }

    public function create(array $fields): false|int
    {
        $params = $this->prepareQueryParams($fields);
        $this->query = "INSERT INTO " . $this->from . " ($params[keys]) VALUES ($params[placeholders])";

        $query = db()->prepare($this->query);

        if (!$query->execute($fields)) {
            return false;
        }

        $query->closeCursor();

        return (int) db()->lastInsertId();
    }

    public function destroy(int $id): bool
    {
        $this->where = ['id' => $id];
        $this->query = "DELETE FROM " . $this->from . " WHERE id = :id";

        $query = db()->prepare($this->query);
        $query->bindParam('id', $id);

        return $query->execute();
    }

    protected function prepareQueryParams(array $fields): array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders)
        ];
    }

    protected function resetQuery(): void
    {
        $this->query = '';
    }

    public function get(): array
    {
        return db()->query($this->query)->fetchAll(PDO::FETCH_CLASS, static::class);
    }

    public function getOne(): static|false
    {
        return db()->query($this->query)->fetchObject(static::class);
    }
}
