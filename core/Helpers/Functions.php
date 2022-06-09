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

function array_pluck(array $array, string $key): array
{
    $return = [];

    foreach ($array as $v) {
        $return[$v[$key]][] = $v;
    }

    return $return;
}

function csvToArray($csvFile): array
{

    $file_to_read = fopen($csvFile, 'r');

    while (!feof($file_to_read) ) {
        $lines[] = fgetcsv($file_to_read, 1000, ',');

    }

    fclose($file_to_read);
    return $lines;
}