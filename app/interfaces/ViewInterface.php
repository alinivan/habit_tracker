<?php

namespace App\Interfaces;

interface ViewInterface {
    public function renderView(string $view, array $data = []): string;
}