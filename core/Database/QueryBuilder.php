<?php

namespace Core\Database;

use Core\Database\Db;

class QueryBuilder implements QueryBuilderInterface
{
    private string $sql;
    private array $values = [];

    public function __construct(?string $table = '')
    {
        if ($table) {
            $this->table_name = $table;
        }
    }

    protected function reset(): void
    {
        $this->values = [];
        $this->sql = '';
    }

    public function select(string $select = '*'): self
    {
        $this->reset();
        $this->sql .= "SELECT $select FROM " . Utilities::toStr($this->table_name);

        return $this;
    }

    public function from(string $table, string $alias = ''): self
    {
        $this->sql = substr($this->sql, 0, strpos($this->sql, 'FROM'));
        $this->sql .= "FROM " . Utilities::toStr($table);

        if ($alias) {
            $this->sql .= " as $alias";
        }

        return $this;
    }

    public function insert(array $params): void
    {
        $columns = array_keys($params);
        $values = [];

        foreach ($columns as &$column) {
            $column = Utilities::toStr($column);
            $values[] = '?';
        }

        $columns = implode(',', $columns);
        $this->sql = "INSERT INTO " . Utilities::toStr($this->table_name) . " ($columns) VALUES (" . implode(',', $values) . ")";

        DB::query($this->sql, array_values($params))->fetchAll();
    }

    public function update(int $id, array $params): void
    {
        $set = [];

        foreach ($params as $column => $value) {
            $set[] = Utilities::toStr($column) . "=?";
        }

        $this->sql = "UPDATE " . Utilities::toStr($this->table_name) . " SET " . implode(',', $set) . " WHERE id=?";

        DB::query($this->sql, array_merge(array_values($params), [$id]))->fetchAll();
    }

    public function destroy(int $id): void
    {
        $this->sql = "DELETE FROM " . Utilities::toStr($this->table_name) . " WHERE id=?";

        DB::query($this->sql, [$id]);
    }


    public function where(array $params): self
    {
        foreach ($params as $column => $value) {
            if (is_array($value)) {
                foreach ($value as $condition => $val) {
                    $clause = "WHERE";

                    if (str_contains($this->sql, 'WHERE')) {
                        $clause = "AND";
                    }

                    $this->sql .= " $clause " . Utilities::toStr($column) . " $condition? ";
                    $this->values[] = $val;
                }
            } else {
                $clause = "WHERE";

                if (str_contains($this->sql, 'WHERE')) {
                    $clause = "AND";
                }

                $this->sql .= " $clause " . Utilities::toStr($column) . "=? ";
                $this->values[] = $value;
            }
        }

        return $this;
    }

    public function whereIn(string $column, QueryBuilder $model): self
    {
        $this->sql .= " WHERE " . Utilities::toStr($column) . " in ($model->sql)";
        $this->values = array_merge($this->values, $model->values);

        return $this;
    }

    public function join(string|array $table, string $on): self
    {
        if (is_array($table)) {
            $this->sql .= " INNER JOIN " . Utilities::toStr($table[0]) . " ";

            if (isset($table[1])) {
                $this->sql .= " as $table[1] ";
            }

        } else {
            $this->sql .= " INNER JOIN " . Utilities::toStr($table) . " ";
        }

        $this->sql .= "on ($on)";

        return $this;
    }

    public function orderBy(string $column, string $order = 'asc'): self
    {
        $this->sql .= " ORDER BY " . Utilities::toStr($column) . " $order";

        return $this;
    }

    public function groupBy(string $column): self
    {
        $this->sql .= " GROUP BY " . Utilities::toStr($column);

        return $this;
    }

    public function limit(int $limit): self
    {
        $this->sql .= " LIMIT $limit";
        return $this;
    }

    public function fetchAll(): bool|array
    {
        return DB::query($this->sql, $this->values)->fetchAll();
    }

    public function fetch(): bool|array
    {
        return DB::query($this->sql, $this->values)->fetch();
    }

}