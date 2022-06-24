<?php

namespace Core\View;

interface ViewInterface {
    public function renderView(string $view, array $data = []): string;
}