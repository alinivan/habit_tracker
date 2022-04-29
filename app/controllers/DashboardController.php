<?php

namespace App\Controllers;

class DashboardController extends AbstractController {
    public function test() {
        echo $this->renderView('index.html.twig');
    }
}