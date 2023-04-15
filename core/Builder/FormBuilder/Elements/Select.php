<?php

namespace Core\Builder\FormBuilder\Elements;

class Select
{
    public static function build(array $fields): array
    {
        return [
            'type' => 'select',
            'id' => $fields['id'] ?? '',
            'name' => $fields['name'] ?? '',
            'label' => $fields['label'] ?? '',
            'class' => $fields['class'] ?? '',
            'value' => $fields['value'] ?? '',
            'span' => $fields['span'] ?? 6,
            'label_class' => $fields['label_class'] ?? '',
            'options' => $fields['options'] ?? [],
            'options_default_value' => $fields['options_default_value'] ?? false
        ];
    }
}