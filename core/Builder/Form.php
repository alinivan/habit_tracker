<?php

namespace Core\Builder;

use Core\Templating\TwigTemplating;

class Form extends TwigTemplating
{
    private array $form;

    public function input(array $fields)
    {
        $this->form['fields'][] = [
            'type' => 'input',
            'input_type' => $fields['type'] ?? 'text',
            'id' => $fields['id'] ?? '',
            'name' => $fields['name'] ?? '',
            'label' => $fields['label'] ?? '',
            'class' => $fields['class'] ?? '',
            'value' => $fields['value'] ?? '',
            'placeholder' => $fields['placeholder'] ?? '',
            'options' => $fields['options'] ?? [],
        ];
    }

    public function select(array $fields)
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

    public function action(string $action)
    {
        $this->form['action'] = $action;
    }

    public function method(string $method)
    {
        $this->form['method'] = $method;
    }

    public function title(string $title)
    {
        $this->form['title'] = $title;
    }

    public function submit(array $fields)
    {
        $this->form['submit'] = [
            'id' => $fields['id'] ?? '',
            'label' => $fields['label'] ?? 'Save',
            'class' => $fields['class'] ?? ''
        ];
    }

    public function getId() {
        if (!isset($this->form['id'])) {
            $this->form['id'] = uniqid();
        }

        return $this->form['id'];
    }

    public function html(): string
    {
        return $this->renderView('app/form.html.twig', ['form' => $this->form]);
    }

}