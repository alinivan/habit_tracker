<?php

namespace App\Controllers;

use App\Models\Page;
use App\Models\PageCategory;
use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;

class PageController extends BaseController
{
    public function index()
    {
        $pagesCategory = PageCategory::all();
        $pages = Page::all();

        echo $this->renderView('app/pages/index.html.twig', [
            'pages' => $pages,
            'pagesCategory' => $pagesCategory
        ]);
    }

    public function new(): void
    {
        $addEditForm = $this->addEditForm('/pages');

        echo $this->renderView('app/pages/new.html.twig', [
            'form' => $addEditForm->getHtml(),
            'form_js' => $addEditForm->getJs()
        ]);
    }

    public function create(): void
    {
        Page::create($_REQUEST);

        redirect('/pages');
    }

    public function show(int $id): void
    {
        echo $this->renderView('app/pages/show.html.twig', [
            'page' => Page::get($id)
        ]);
    }

    public function edit(int $id): void
    {
        $page = Page::get($id);

        $form = $this->addEditForm("/pages/$id", $page);

        echo $this->renderView('app/pages/edit.html.twig', [
            'page' => $page,
            'form' => $form->getHtml(),
            'form_js' => $form->getJs($page['content'])
        ]);
    }

    public function view(int $id): void
    {
        echo $this->renderView('app/pages/view.html.twig', [
            'page' => Page::get($id)
        ]);
    }

    public function update(int $id): void
    {
        Page::modify($id, $_REQUEST);
        redirect('/pages');
    }

    public function destroy(int $id): void
    {
        Page::delete($id);
        redirect('/pages');
    }

    public function addEditForm(string $action, array $page = []): FormBuilder
    {
        $form = new FormBuilder();
        $form->setAction($action);
        $form->setMethod('POST');
        $form->addInput([
            'type' => 'text',
            'name' => 'name',
            'label' => 'Name',
            'value' => $page['name'] ?? '',
        ]);

        $pagesCategory = PageCategory::all();
        foreach ($pagesCategory as &$v) {
            $v['value'] = $v['id'];
        }

        $form->addSelect([
            'label' => 'Category',
            'name' => 'page_category_id',
            'value' => $page['page_category_id'] ?? 0,
            'options' => $pagesCategory,
            'options_default_value' => true
        ]);

        $form->addText([
            'name' => 'content',
            'span' => 12,
            'value' => $page['content'] ?? ''
        ]);

        $form->setSubmit(['label' => 'Save']);

        return $form;
    }

    public function homePage()
    {
        echo $this->renderView('web/index.html.twig');
    }

    public function notFound()
    {
        echo $this->renderView('app/pages/not_found.html.twig');
    }
}