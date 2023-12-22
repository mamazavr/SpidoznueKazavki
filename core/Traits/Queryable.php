<?php

namespace Core\Traits;

use PDO;

trait Queryable
{
    protected static ?string $tableName = null;
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

    /**
     * INSERT INTO table_name
     * (columns...) [placeholders]
     * VALUES
     * (values....) [placeholders]
     * @param array $fields
     * [
     *  'name' => '...',
     *  'content' => '...'
     * ]
     * @return false|int
     */
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
        $query->bindParam('id', $id, PDO::PARAM_INT);

        return $query->execute();
    }

    protected function prepareQueryParams(array $fields): array
    {
        $keys = array_keys($fields);
        $placeholders = preg_filter('/^/', ':', $keys);

        return [
            'keys' => implode(', ', $keys),
            'placeholders' => implode(', ', $placeholders),
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

    /**
     * Add WHERE clause to the query.
     *
     * @param array $conditions
     * @return static
     */
    public function where(array $conditions): static
    {
        $this->resetQuery();
        $this->where = $conditions;

        $whereClause = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($this->where)));
        $this->query = "SELECT " . implode(', ', $this->select) . " FROM " . $this->from . " WHERE $whereClause ";

        $this->commands[] = 'where';

        return $this;
    }

    /**
     * Add ORDER BY clause to the query.
     *
     * @param string $column
     * @param string $direction
     * @return static
     */
    public function orderBy(string $column, string $direction = 'ASC'): static
    {
        $this->resetQuery();
        $this->orderBy = "ORDER BY $column $direction ";
        $this->query .= $this->orderBy;

        $this->commands[] = 'orderBy';

        return $this;
    }

    /**
     * Add LIMIT clause to the query.
     *
     * @param int $limit
     * @return static
     */
    public function limit(int $limit): static
    {
        $this->resetQuery();
        $this->limit = "LIMIT $limit ";
        $this->query .= $this->limit;

        $this->commands[] = 'limit';

        return $this;
    }

    /**
     * Update records in the database.
     *
     * @param array $fields
     * @return bool
     */
    public function update(array $fields): bool
    {
        $this->query = "UPDATE " . $this->from . " SET ";

        $setClause = implode(', ', array_map(fn ($key) => "$key = :$key", array_keys($fields)));
        $this->query .= $setClause;

        if (!empty($this->where)) {
            $whereClause = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($this->where)));
            $this->query .= " WHERE $whereClause";
        }

        $query = db()->prepare($this->query);

        $params = array_merge($fields, $this->where);
        if (!$query->execute($params)) {
            return false;
        }

        $query->closeCursor();

        return true;
    }
}
