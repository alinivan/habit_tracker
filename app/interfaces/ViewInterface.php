<?php

namespace App\interfaces;

interface ViewInterface {
    public function renderView(string $view, array $data = []): string;
}