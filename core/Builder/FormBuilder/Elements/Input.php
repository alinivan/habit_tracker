<?php

namespace Core\Builder\FormBuilder\Elements;

class Input
{
    public static function build(array $fields): array
    {
        return [
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
}