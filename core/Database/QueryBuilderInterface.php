<?php

namespace Core\Database;

interface QueryBuilderInterface
{
    public function from(string $table, string $alias = ''): self;

    public function select(string $select = '*'): self;

    public function insert(array $params): void;

    public function update(int $id, array $params): void;

    public function destroy(int $id): void;

    public function where(array $params): self;

    public function whereIn(string $column, QueryBuilder $model): self;

    public function join(string|array $table, string $on): self;

    public function orderBy(string $column, string $order = 'asc'): self;

    public function groupBy(string $column): self;

    public function limit(int $limit): self;

    public function fetchAll(): bool|array;

    public function fetch(): bool|array;
}