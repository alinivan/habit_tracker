<?php

namespace App\Models;

use Core\Auth;
use Core\Database\Model;

class Habit extends Model
{
    protected string $table_name = 'habits';

    public function create(array $request): void
    {
        $this->insert([
            'name' => $request['name'],
            'value_type' => $request['value_type'],
            'category_id' => $request['category_id'],
            'min_value' => $request['min_value'],
            'order' => $request['order'],
            'active' => $request['active'],
            'measurement' => $request['measurement'],
            'is_productive' => $request['is_productive'],
            'points' => $request['points'],
            'user_id' => Auth::getUserId()
        ]);
    }

    public function all(): bool|array
    {
        return $this
            ->select()
            ->where(['user_id' => Auth::getUserId()])
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
            'value_type' => $request['value_type'],
            'category_id' => $request['category_id'],
            'min_value' => $request['min_value'],
            'order' => $request['order'],
            'active' => $request['active'],
            'measurement' => $request['measurement'],
            'is_productive' => $request['is_productive'],
            'points' => $request['points']
        ]);
    }

    public function delete(int $id): void
    {
        $this->destroy($id);
    }

    public function allByCategoryId(int $category_id): bool|array
    {
        return $this
            ->select()
            ->where(['category_id' => $category_id, 'active' => 1])
            ->orderBy('order')
            ->fetchAll();
    }

    public function allWithCategory(): bool|array
    {
        $categories = (new Category())->all();

        foreach ($categories as $k => $v) {
            $habits = $this->allByCategoryId($v['id']);

            if ($habits) {
                $categories[$k]['habits'] = $habits;
            }
        }

        return $categories;
    }

    public function getByName(string $string): bool|array
    {
        return $this
            ->select()
            ->where([
                'user_id' => Auth::getUserId(),
                'name' => $string
            ])
            ->fetch();
    }
}