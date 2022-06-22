<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Model;

class Category extends Model
{
    protected string $table_name = 'category';

    public function create(array $request): void
    {
        $this->insert([
            'name' => $request['name'],
            'color' => $request['color'],
            'order' => $request['order'],
            'user_id' => Auth::getUserId()
        ]);
    }

    public function all(): bool|array
    {
        return $this
            ->select()
            ->where(['user_id' => Auth::getUserId()])
            ->orderBy('order')
            ->fetchAll();
    }

    public function get(int $id): bool|array
    {
        return $this
            ->select()
            ->where(['id' => $id])
            ->fetch();
    }

    public function modify(int $id, array $request): void
    {
        $this->update($id, [
            'name' => $request['name'],
            'color' => $request['color'],
            'order' => $request['order'],
            'user_id' => Auth::getUserId()
        ]);
    }

    public function delete(int $id): void
    {
        $this->destroy($id);
    }
}