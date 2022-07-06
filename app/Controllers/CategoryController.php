<?php

namespace App\Controllers;

use App\Models\Category;
use Core\Base\BaseController;
use Core\Builder\Form;
use Core\Builder\Table;

class CategoryController extends BaseController
{
    public function index()
    {
        $categories = Category::all();
        $table = new Table();
        $table->setHeaders([
            'Name', 'Color', ' '
        ]);

        foreach ($categories as $category) {
            $editButton = '<a href="/category/'.$category['id'].'/edit" class="text-indigo-600 hover:text-indigo-900">Edit</a>';
            $deleteButton = '<a href="/categories/'.$category['id'].'/delete" class="text-red-600 hover:text-red-900">Delete</a>';
            $table->addRow([$category['name'], $category['color'], $editButton . $deleteButton]);
        }

        echo $this->renderView('app/category/index.html.twig', [
            'categoriesTable' => $table->getHtml()
        ]);
    }

    public function new()
    {
        $add_edit_form = $this->addEditForm('/categories');

        echo $this->renderView('app/category/new.html.twig', ['form' => $add_edit_form->getHtml()]);
    }

    public function create()
    {
        Category::create($_REQUEST);

        redirect('/categories');
    }

    public function show(int $id)
    {
        echo $this->renderView('app/category/show.html.twig', ['category' => Category::get($id)]);
    }

    public function edit(int $id)
    {
        $category = Category::get($id);
        $add_edit_form = $this->addEditForm("/categories/$id", $category);

        echo $this->renderView('app/category/edit.html.twig', ['category' => $category, 'form' => $add_edit_form->getHtml()]);
    }

    public function update(int $id)
    {
        Category::modify($id, $_REQUEST);
        redirect('/categories');
    }

    public function destroy(int $id)
    {
        Category::delete($id);
        redirect('/categories');
    }

    public function addEditForm(string $action, array $category = []): Form
    {
        $form = new Form();
        $form->setAction($action);
        $form->setMethod('POST');
        $form->addInput([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $category['name'] ?? '',
        ]);

        $color_options = [
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
            ],
            [
                'value' => 'indigo',
                'name' => 'indigo'
            ],

        ];

        $form->addSelect([
            'label' => 'Color',
            'name' => 'color',
            'value' => $category['color'] ?? '',
            'options' => $color_options
        ]);

        $form->addInput([
            'type' => 'number',
            'label' => 'Order',
            'name' => 'order',
            'value' => $category['order'] ?? 0
        ]);
        $form->setSubmit(['label' => 'Save']);

        return $form;
    }
}