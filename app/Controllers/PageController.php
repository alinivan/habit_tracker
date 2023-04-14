<?php

namespace App\Controllers;

use Core\Base\BaseController;
use Core\Builder\FormBuilder\FormBuilder;

class PageController extends BaseController
{
    public function homePage()
    {
        echo $this->renderView('web/index.html.twig');
    }

    public function notFound(): void
    {
        echo $this->renderView('app/pages/not_found.html.twig');
    }
}