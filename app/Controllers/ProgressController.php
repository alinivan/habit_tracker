<?php

namespace App\Controllers;

use App\Services\ChartService;
use Core\Base\BaseController;
use Core\Charts\Chart;

class ProgressController extends BaseController
{
    public function index()
    {
        $dataset = ChartService::productiveDataset();

        $productivity_chart = new Chart();
        $productivity_chart->setTitle('Productivity Points');
        $productivity_chart->addDataset(array_values($dataset));
        $productivity_chart->setLabels(array_keys($dataset));

        echo $this->renderView('app/progress/index.html.twig', [
            'progress_chart' => $productivity_chart->getHtmlAndJs()
        ]);
    }
}