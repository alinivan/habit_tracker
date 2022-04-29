<?php

function pre($data): void
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}