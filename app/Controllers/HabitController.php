<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Habit;
use Core\Builder\Form;

class HabitController extends AbstractController
{
    public function index()
    {
        $habits = Habit::all();
        $categories = array_remap(Category::all(), 'id');

        echo $this->renderView('app/habit/index.html.twig', [
            'habits' => $habits,
            'categories' => $categories
        ]);
    }

    public function new()
    {
        $form = $this->addEditForm('/habits');

        echo $this->renderView('app/habit/new.html.twig', ['form' => $form->html()]);
    }

    public function create()
    {
        Habit::insert($_REQUEST);

        redirect('/habits');
    }

    public function show(int $id)
    {
        $habit = Habit::get($id);

        echo $this->renderView('app/habit/show.html.twig', ['habit' => $habit]);
    }

    public function edit(int $id)
    {
        $habit = Habit::get($id);

        $form = $this->addEditForm("/habits/$id", $habit);

        echo $this->renderView('app/habit/edit.html.twig', ['habit' => $habit, 'form' => $form->html()]);
    }

    public function update(int $id)
    {
        Habit::update($id, $_REQUEST);
        redirect('/habits');
    }

    public function destroy(int $id)
    {
        Habit::destroy($id);
        redirect('/habits');
    }

    public function addEditForm(string $action, array $habit = []): Form
    {
        $form = new Form();
        $form->action($action);
        $form->method('POST');
        $form->input([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $habit['name'] ?? '',
        ]);

        $form->input([
            'type' => 'number',
            'name' => 'min_value',
            'label' => 'Min value',
            'value' => $habit['min_value'] ?? 0,
        ]);

        $form->input([
            'type' => 'number',
            'name' => 'order',
            'label' => 'Order',
            'value' => $habit['order'] ?? 0,
        ]);

        $form->input([
            'type' => 'text',
            'name' => 'points',
            'label' => 'Points',
            'value' => $habit['points'] ?? 0,
        ]);

        $form->select([
            'name' => 'active',
            'label' => 'Active',
            'value' => $habit['active'] ?? 1,
            'options' => [['name' => 'Yes', 'value' => 1], ['name' => 'No', 'value' => 0]]
        ]);

        $form->select([
            'name' => 'measurement',
            'label' => 'Measurement',
            'value' => $habit['measurement'] ?? 'min',
            'options' => [['name' => 'min', 'value' => 'min'], ['name' => 'kg', 'value' => 'kg']]
        ]);

        $value_types = [
            [
                'value' => 'number',
                'name' => 'Number'
            ],
            [
                'value' => 'boolean',
                'name' => 'Boolean'
            ]
        ];

        $categories = Category::all();
        foreach ($categories as $k => $v) {
            $categories[$k]['value'] = $v['id'];
        }

        $form->select([
            'label' => 'Category',
            'name' => 'category_id',
            'value' => $habit['category_id'] ?? 0,
            'options' => $categories
        ]);

        $form->select([
            'label' => 'Productive',
            'name' => 'is_productive',
            'value' => $habit['is_productive'] ?? 0,
            'options' => [['name' => 'Yes', 'value' => 1], ['name' => 'No', 'value' => 0]]
        ]);

        $form->select([
            'label' => 'Type',
            'name' => 'value_type',
            'value' => $habit['value_type'] ?? 'numeric',
            'options' => $value_types
        ]);
        $form->submit(['label' => 'Save']);

        return $form;
    }
}