<?php

namespace App\Controllers;

class DashboardController extends AbstractController {
    public function test() {
        echo $this->renderView('index.html.twig', ['user_logged_in' => $_SESSION['logged_in']]);
    }
}