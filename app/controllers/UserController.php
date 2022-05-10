<?php

namespace App\Controllers;

use App\Models\User;
use Core\Auth;

class UserController extends AbstractController
{

    public function loginPage()
    {
        if (Auth::userLoggedIn()) {
            redirect('/');
        }

        echo $this->renderView('login.html.twig');
    }

    public function registerPage()
    {
        if (Auth::userLoggedIn()) {
            redirect('/');
        }

        echo $this->renderView('register.html.twig');
    }

    public function register() {
        $user = User::register($_REQUEST);

        Auth::login($user);

        redirect('/');
    }

    public function login()
    {
        $user = User::getUserForLogin($_REQUEST);

        if (!empty($user)) {
            Auth::login($user);

            redirect('/');
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