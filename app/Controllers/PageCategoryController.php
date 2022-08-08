<?php

namespace App\Controllers;

use App\Models\PageCategory;
use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;

class PageCategoryController extends BaseController
{
    public function index()
    {
        $pagesCategory = PageCategory::all();

        echo $this->renderView('app/pages_category/index.html.twig', [
            'pagesCategory' => $pagesCategory
        ]);
    }

    public function new(): void
    {
        $addEditForm = $this->addEditForm('/pages-category');

        echo $this->renderView('app/pages_category/new.html.twig', ['form' => $addEditForm->getHtml()]);
    }

    public function create(): void
    {
        PageCategory::create($_REQUEST);

        redirect('/pages-category');
    }

    public function show(int $id): void
    {
        echo $this->renderView('app/pages_category/show.html.twig', [
            'pageCategory' => PageCategory::get($id)
        ]);
    }

    public function edit(int $id): void
    {
        $pageCategory = PageCategory::get($id);

        $form = $this->addEditForm("/pages-category/$id", $pageCategory);

        echo $this->renderView('app/pages_category/edit.html.twig', [
            'pageCategory' => $pageCategory,
            'form' => $form->getHtml()
        ]);
    }

    public function update(int $id): void
    {
        PageCategory::modify($id, $_REQUEST);
        redirect('/pages-category');
    }

    public function destroy(int $id): void
    {
        PageCategory::delete($id);
        redirect('/pages-category');
    }

    public function addEditForm(string $action, array $pageCategory = []): FormBuilder
    {
        $form = new FormBuilder();
        $form->setAction($action);
        $form->setMethod('POST');
        $form->addInput([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $pageCategory['name'] ?? '',
        ]);

        $pagesCategory  = PageCategory::all();
        foreach ($pagesCategory as &$v) {
            $v['value'] = $v['id'];
        }

        $form->addSelect([
            'label' => 'Category',
            'name' => 'parent_id',
            'value' => $pageCategory['parent_id'] ?? 0,
            'options' => $pagesCategory,
            'options_default_value' => true
        ]);


        $form->setSubmit(['label' => 'Save']);

        return $form;
    }
}