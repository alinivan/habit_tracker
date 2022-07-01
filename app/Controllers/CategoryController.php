<?php

namespace App\Controllers;

use App\Models\Category;
use Core\Base\BaseController;
use Core\Builder\Form;

class CategoryController extends BaseController
{
    public function index()
    {
        echo $this->renderView('app/category/index.html.twig', [
            'categories' => Category::all()
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