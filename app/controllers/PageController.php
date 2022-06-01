<?php

namespace App\Controllers;

class PageController extends AbstractController {
    public function notFound() {
        echo $this->renderView('app/not_found.html.twig');
    }
}