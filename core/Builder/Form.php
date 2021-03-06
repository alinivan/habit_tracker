<?php

namespace Core\Builder;

use Core\View\ViewManager;

class Form extends ViewManager
{
    private array $form;

    public function addInput(array $fields)
    {
        $this->form['fields'][] = [
            'type' => 'input',
            'input_type' => $fields['type'] ?? 'text',
            'id' => $fields['id'] ?? '',
            'name' => $fields['name'] ?? '',
            'label' => $fields['label'] ?? '',
            'class' => $fields['class'] ?? '',
            'label_class' => $fields['label_class'] ?? '',
            'value' => $fields['value'] ?? '',
            'placeholder' => $fields['placeholder'] ?? '',
            'options' => $fields['options'] ?? [],
        ];
    }

    public function addSelect(array $fields)
    {
        $this->form['fields'][] = [
            'type' => 'select',
            'id' => $fields['id'] ?? '',
            'name' => $fields['name'] ?? '',
            'label' => $fields['label'] ?? '',
            'class' => $fields['class'] ?? '',
            'value' => $fields['value'] ?? '',
            'options' => $fields['options'] ?? [],
        ];
    }

    public function setAction(string $action)
    {
        $this->form['action'] = $action;
    }

    public function setMethod(string $method)
    {
        $this->form['method'] = $method;
    }

    public function setTitle(string $title)
    {
        $this->form['title'] = $title;
    }

    public function setSubmit(array $fields)
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