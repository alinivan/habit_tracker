<?php

namespace Core\Database;

class Utilities
{
    public static function toStr(string $string): string
    {
        if (str_contains($string, '.')) {
            return $string;
        }

        return "`$string`";
    }
}