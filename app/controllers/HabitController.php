<?php

namespace App\Controllers;

use App\Models\Habit;
use Core\Builder\Form;

class HabitController extends AbstractController
{
    public function index()
    {
        $habits = Habit::all();

        echo $this->renderView('app/habit/index.html.twig', ['habits' => $habits]);
    }

    public function new()
    {
        $form = new Form();
        $form->action('/habits');
        $form->method('POST');
        $form->input([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name'
        ]);

        $value_types = [
            [
                'value' => 'number',
                'label' => 'Number'
            ],
            [
                'value' => 'boolean',
                'label' => 'Boolean'
            ]
        ];
        $form->select([
            'label' => 'Type',
            'name' => 'value_type',
            'options' => $value_types
        ]);
        $form->submit(['label' => 'Save']);

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

        $form = new Form();
        $form->action("/habits/$id");
        $form->method('POST');
        $form->input([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $habit['name'],
        ]);

        $value_types = [
            [
                'value' => 'number',
                'label' => 'Number'
            ],
            [
                'value' => 'boolean',
                'label' => 'Boolean'
            ]
        ];
        $form->select([
            'label' => 'Type',
            'name' => 'value_type',
            'value' => $habit['value_type'],
            'options' => $value_types
        ]);
        $form->submit(['label' => 'Save']);

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
}