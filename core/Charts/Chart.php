<?php

namespace Core\Charts;

use Core\View\ViewManager;

class Chart extends ViewManager
{
    public function __construct()
    {
        $this->id = uniqid();
        parent::__construct();
    }

    public string $id;
    public string $title;
    public string $type = 'line';
    public int $width = 400;
    public int $height = 150;
    public array $datasets = [];
    public array $labels = [];

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function setTitle(string $title)
    {
        $this->title = $title;
    }

    public function setWidth(int $width)
    {
        $this->width = $width;
    }

    public function setHeight(int $height)
    {
        $this->height = $height;
    }

    public function addDataset(array $dataset)
    {
        $this->datasets[] = $dataset;
    }

    public function setLabels(array $labels)
    {
        $this->labels = $labels;
    }

    public function getDatasets(): array
    {
        return $this->datasets;
    }

    private function getLabels(): array
    {
        return $this->labels;
    }

    public function getHtmlAndJs(): array
    {
        return [
            'js' => $this->getJs(),
            'html' => $this->getHtml()
        ];
    }

    public function getJs(): string
    {
        return $this->renderView('app/charts/chart_js.html.twig', [
            'chart' => [
                'id' => $this->id,
                'title' => $this->title,
                'type' => $this->type,
                'datasets' => $this->getDatasets(),
                'labels' => $this->getLabels()
            ]
        ]);
    }

    public function getHtml(): string
    {
        return $this->renderView('app/charts/chart_html.html.twig', [
            'chart' => [
                'id' => $this->id,
                'width' => $this->width,
                'height' => $this->height
            ]
        ]);
    }

}