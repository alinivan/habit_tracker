<?php

namespace Core\Builder;

use Core\View\ViewManager;

class Table extends ViewManager
{
    private array $headers;
    private array $rows;

    public function getHtml(): string
    {
        $data = [
            'headers' => $this->headers,
            'rows' => $this->rows
        ];

        return $this->renderView('core/builder/table.html.twig', $data);
    }

    public function setHeaders(array $array)
    {
        $this->headers = $array;
    }

    public function addRow(array $array)
    {
        $this->rows[] = $array;
    }


}