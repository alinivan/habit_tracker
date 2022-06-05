<?php

namespace App\Controllers;

class PageController extends AbstractController
{
    public function index()
    {
        echo $this->renderView('web/index.html.twig');
    }

    public function notFound()
    {
        echo $this->renderView('app/pages/not_found.html.twig');
    }
}