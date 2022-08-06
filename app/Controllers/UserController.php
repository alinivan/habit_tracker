<?php

namespace App\Controllers;

use App\Models\Tracker;
use App\Models\User;
use Core\Auth\Auth;
use Core\Base\BaseController;

class UserController extends BaseController
{
    public function loginPage()
    {
        if (Auth::userIsLoggedIn()) {
            redirect('/dashboard');
        }

        echo $this->renderView('web/pages/login.html.twig');
    }

    public function registerPage()
    {
        if (Auth::userIsLoggedIn()) {
            redirect('/dashboard');
        }

        echo $this->renderView('web/pages/register.html.twig');
    }

    public function register()
    {
        $user = User::register($_REQUEST);

        Auth::login($user);

        redirect('/dashboard');
    }

    public function login()
    {
        $user = User::getUserForLogin($_REQUEST);

        if (!empty($user)) {
            Auth::login($user);

            redirect('/dashboard');
        } else {

        }
    }

    public function logout()
    {
        Auth::logout();
        redirect('/');
    }
}