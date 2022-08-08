<?php

namespace Core\Builder\FormBuilder\Elements;

class Text
{
    public static function build(array $fields): array
    {
        return [
            'type' => 'text',
            'name' => $fields['name'] ?? '',
            'value' => $fields['value'] ?? '',
            'span' => $fields['span'] ?? 6
        ];
    }
}