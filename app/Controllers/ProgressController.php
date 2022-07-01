<?php

namespace App\Controllers;

use App\Services\ChartService;
use App\Services\ProgressService;
use Core\Base\BaseController;
use Core\Charts\Chart;

class ProgressController extends BaseController
{
    public function index()
    {
        $weekly = false;
        if (isset($_REQUEST['weekly'])) {
            $weekly = true;
        }

        $motivation = (new ProgressService())->test();
        $dataset = ChartService::productiveDataset($weekly);

        $productivity_chart = new Chart();
        $productivity_chart->setTitle('Productivity Points');
        $productivity_chart->addDataset(array_values($dataset));
        $productivity_chart->setLabels(array_keys($dataset));

        $dataset_kg = ChartService::habitChart('Kg', '2022-06-16');
        $kg_chart = new Chart();
        $kg_chart->setTitle('Gaining Weight');
        $kg_chart->addDataset(array_values($dataset_kg));
        $kg_chart->setLabels(array_keys($dataset_kg));

        echo $this->renderView('app/progress/index.html.twig', [
            'motivation' => $motivation,
            'progress_chart' => $productivity_chart->getHtmlAndJs(),
            'kg_chart' => $kg_chart->getHtmlAndJs()
        ]);
    }
}