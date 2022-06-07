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

        $value_types = [
            [
                'value' => 'red',
                'name' => 'red'
            ],
            [
                'value' => 'green',
                'name' => 'green'
            ],
            [
                'value' => 'gray',
                'name' => 'gray'
            ],
            [
                'value' => 'blue',
                'name' => 'blue'
            ]
        ];
        $form->select([
            'label' => 'Color',
            'name' => 'color',
            'value' => $category['color'] ?? '',
            'options' => $value_types
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
        $category = Category::get($id);

        $form = new Form();
        $form->action("/categories/$id");
        $form->method('POST');
        $form->input([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $category['name'],
        ]);

        $value_types = [
            [
                'value' => 'red',
                'name' => 'red'
            ],
            [
                'value' => 'green',
                'name' => 'green'
            ],
            [
                'value' => 'gray',
                'name' => 'gray'
            ],
            [
                'value' => 'blue',
                'name' => 'blue'
            ]
        ];

        $form->select([
            'label' => 'Color',
            'name' => 'color',
            'value' => $category['color'] ?? '',
            'options' => $value_types
        ]);
        $form->submit(['label' => 'Save']);

        echo $this->renderView('app/category/edit.html.twig', ['habit' => $category, 'form' => $form->html()]);
    }

    public function update(int $id)
    {
        Category::update($id, $_REQUEST);
        redirect('/categories');
    }

    public function destroy(int $id)
    {
        Category::destroy($id);
        redirect('/categories');
    }
}