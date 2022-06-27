<?php

namespace Core\Charts;

use Core\View\ViewManager;

class Chart extends ViewManager
{
    public string $id;
    public string $title;
    public string $type = 'line';
    public int $width = 400;
    public int $height = 150;

    public function test(): array
    {
        $this->id = '123456';
        $this->title = 'Title test';

        return [
            'js' => $this->js(),
            'html' => $this->html()
        ];
    }

    public function js(): string
    {
        return $this->renderView('app/charts/chart_js.html.twig', [
            'chart' => [
                'id' => $this->id,
                'title' => $this->title,
                'type' => $this->type,
                'data' => $this->getData()
            ]
        ]);
    }

    public function html(): string
    {
        return $this->renderView('app/charts/chart_html.html.twig', [
            'chart' => [
                'id' => $this->id,
                'width' => $this->width,
                'height' => $this->height
            ]
        ]);
    }

    public function getData(): array
    {
        return [
            'labels' => [100, 101, 102, 103],
            'data' => [3, 1, 2, 4]
        ];
    }

}