<?php

namespace Core\Database;
use Core\Database\Db;

class QueryBuilder
{
    private string $sql;
    private array $values = [];

    public function __construct(?string $table = '')
    {
        if ($table) {
            $this->table_name = $table;
        }
    }

    public function from(string $table, string $alias = ''): self
    {
        $this->sql = substr($this->sql, 0, strpos($this->sql, 'FROM'));
        $this->sql .= "FROM `$table`";

        if ($alias) {
            $this->sql .= " as $alias";
        }

        return $this;
    }

    public function select(string $select = '*'): self
    {
        $this->values = [];
        $this->sql = "SELECT $select FROM `$this->table_name`";
        return $this;
    }

    public function insert(array $params): void
    {
        $columns = array_keys($params);
        $values = [];

        foreach ($columns as &$column) {
            $column = "`$column`";
            $values[] = '?';
        }

        $columns = implode(',', $columns);
        $this->sql = "INSERT INTO `$this->table_name` ($columns) VALUES (" . implode(',', $values) . ")";

        DB::query($this->sql, array_values($params))->fetchAll();
    }

    public function update(int $id, array $params): void
    {
        $set = [];

        foreach ($params as $column => $value) {
            $set[] = "`$column`=?";
        }

        $this->sql = "UPDATE `$this->table_name` SET " . implode(',', $set) . " WHERE id=?";

        DB::query($this->sql, array_merge(array_values($params), [$id]))->fetchAll();
    }

    public function destroy(int $id): void
    {
        $this->sql = "DELETE FROM `$this->table_name` WHERE id=?";
        DB::query($this->sql, [$id]);
    }


    public function where(array $params): self
    {
        foreach ($params as $column => $value) {
            if (is_array($value)) {
                foreach ($value as $condition => $val) {
                    if (str_contains($this->sql, 'WHERE')) {

                        if (str_contains($column, '.')) {
                            $this->sql .= " AND $column $condition? ";
                        } else {
                            $this->sql .= " AND `$column`$condition? ";
                        }

                    } else {
                        if (str_contains($column, '.')) {
                            $this->sql .= " WHERE $column $condition? ";
                        } else {
                            $this->sql .= " WHERE `$column`$condition? ";
                        }
                    }
                    $this->values[] = $val;
                }
            } else {
                if (str_contains($this->sql, 'WHERE')) {

                    if (str_contains($column, '.')) {
                        $this->sql .= " AND $column=? ";
                    } else {
                        $this->sql .= " AND `$column`=? ";
                    }

                } else {

                    if (str_contains($column, '.')) {
                        $this->sql .= " WHERE $column=? ";
                    } else {
                        $this->sql .= " WHERE `$column`=? ";
                    }
                }
                $this->values[] = $value;
            }

        }
        return $this;
    }

    public function whereIn(string $column, QueryBuilder $model): self
    {
        $this->sql .= " WHERE `$column` in ($model->sql)";
        $this->values = array_merge($this->values, $model->values);

        return $this;
    }

    public function join(string|array $table, string $on): self
    {

        if (is_array($table)) {
            $this->sql .= " INNER JOIN `$table[0]` ";

            if (isset($table[1])) {
                $this->sql .= " as $table[1] ";
            }

        } else {
            $this->sql .= " INNER JOIN `$table` ";
        }

        $this->sql .= "on ($on)";

        return $this;

    }

    public function orderBy(string $column, string $order = 'asc'): self
    {
        if (str_contains($column, '.')) {
            $this->sql .= " ORDER BY $column $order";
        } else {
            $this->sql .= " ORDER BY `$column` $order";
        }

        return $this;
    }

    public function groupBy(string $column, string $order = 'asc'): self
    {
        if (str_contains($column, '.')) {
            $this->sql .= " GROUP BY $column $order";
        } else {
            $this->sql .= " GROUP BY `$column` $order";
        }

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