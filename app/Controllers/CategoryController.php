<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Habit;
use Core\Builder\Form;

class CategoryController extends AbstractController
{
    public function index()
    {
        $categories = Category::all();

        echo $this->renderView('app/category/index.html.twig', ['categories' => $categories]);
    }

    public function new()
    {
        $form = new Form();
        $form->action('/categories');
        $form->method('POST');
        $form->input([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name'
        ]);

        $form->submit(['label' => 'Save']);

        echo $this->renderView('app/category/new.html.twig', ['form' => $form->html()]);
    }

    public function create()
    {
        Category::insert($_REQUEST);

        redirect('/categories');
    }

    public function show(int $id)
    {
        $habit = Category::get($id);

        echo $this->renderView('app/category/show.html.twig', ['habit' => $habit]);
    }

    public function edit(int $id)
    {
        $habit = Category::get($id);

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

        echo $this->renderView('app/category/edit.html.twig', ['habit' => $habit, 'form' => $form->html()]);
    }

    public function update(int $id)
    {
        Category::update($id, $_REQUEST);
        redirect('/habits');
    }

    public function destroy(int $id)
    {
        Habit::destroy($id);
        redirect('/habits');
    }
}