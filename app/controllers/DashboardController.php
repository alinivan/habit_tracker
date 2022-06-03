<?php

namespace App\Controllers;

class DashboardController extends AbstractController
{
    public function index()
    {
        echo $this->renderView('app/dashboard/index.html.twig');
    }
}