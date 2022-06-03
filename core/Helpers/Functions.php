<?php

use JetBrains\PhpStorm\NoReturn;

function pre($data, string $string = ''): void
{
    if (!empty($string)) {
        echo "<b>$string</b>";
    }
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

#[NoReturn] function dd($data): void
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    exit;
}

function redirect($string): void
{
    header("Location: $string");
}