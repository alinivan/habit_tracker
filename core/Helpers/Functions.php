<?php

function pre($data, string $string = ''): void
{
    if (!empty($string)) {
        echo "<b>$string</b>";
    }
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

function dd($data): void
{
    pre($data);
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

function array_remap(array $array, string $key): array
{
    $return = [];

    foreach ($array as $v) {
        $return[$v[$key]] = $v;
    }

    return $return;
}

function csvToArray($csvFile): array
{
    $lines = [];
    $file_to_read = fopen($csvFile, 'r');

    while (!feof($file_to_read) ) {
        $lines[] = fgetcsv($file_to_read, 1000, ',');
    }

    fclose($file_to_read);

    return $lines;
}

function dateRange(string $dateFrom = '', string $dateTo = ''): array
{
    if ($dateFrom === '') {
        $dateFrom = IMPORT_START_DATE;
    }

    if ($dateTo === '') {
        $dateTo = date('Y-m-d', strtotime('+1 day'));
    }

    $range = [];

    $period = new DatePeriod(
        new DateTime($dateFrom),
        new DateInterval('P1D'),
        new DateTime($dateTo)
    );

    foreach ($period as $value) {
        $range[] = $value->format('Y-m-d');
    }

    return $range;
}

