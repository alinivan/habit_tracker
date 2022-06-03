<?php

namespace App\Controllers;

use App\Models\User;
use Core\Auth;

class UserController extends AbstractController
{

    public function loginPage()
    {
        if (Auth::userLoggedIn()) {
            redirect('/dashboard');
        }

        echo $this->renderView('web/pages/login.html.twig');
    }

    public function registerPage()
    {
        if (Auth::userLoggedIn()) {
            redirect('/dashboard');
        }

        echo $this->renderView('web/pages/register.html.twig');
    }

    public function register() {
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
            // user/pass gresite
        }
    }

    public function logout()
    {
        Auth::logout();
        redirect('/');
    }
}