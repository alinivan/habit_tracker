<?php

namespace App\Controllers;

use Core\Base\BaseController;

class PageController extends BaseController
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