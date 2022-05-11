<?php

namespace App\Controllers;

class DashboardController extends AbstractController
{
    public function test()
    {
        if ($_SESSION['logged_in']) {
            echo $this->renderView('app/dashboard/index.html.twig', ['user_logged_in' => $_SESSION['logged_in']]);
        } else {
            echo $this->renderView('web/index.html.twig', ['user_logged_in' => $_SESSION['logged_in']]);
        }
    }
}