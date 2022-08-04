<?php

namespace Core\Builder\FormBuilder;

use Core\Builder\FormBuilder\Elements\Input;
use Core\Builder\FormBuilder\Elements\Select;
use Core\View\ViewManager;

class FormBuilder extends ViewManager implements FormBuilderInterface
{
    private array $form;

    private function addField(array $field): void
    {
        $this->form['fields'][] = $field;
    }

    public function addInput(array $fields): void
    {
        $this->addField(Input::build($fields));
    }

    public function addSelect(array $fields): void
    {
        $this->addField(Select::build($fields));
    }

    public function setAction(string $action): void
    {
        $this->form['action'] = $action;
    }

    public function setMethod(string $method): void
    {
        $this->form['method'] = $method;
    }

    public function setTitle(string $title): void
    {
        $this->form['title'] = $title;
    }

    public function setSubmit(array $fields): void
    {
        $this->form['submit'] = [
            'id' => $fields['id'] ?? '',
            'label' => $fields['label'] ?? 'Save',
            'class' => $fields['class'] ?? ''
        ];
    }

    public function getId(): string
    {
        if (!isset($this->form['id'])) {
            $this->form['id'] = uniqid();
        }

        return $this->form['id'];
    }

    public function getHtml(): string
    {
        return $this->renderView('core/builder/form.html.twig', ['form' => $this->form]);
    }

}