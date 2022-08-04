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

    public function import()
    {
        $csvFile = APP_ROOT . '/storage/uploads/tracker.csv';
        $csv = csv_to_array($csvFile);

        foreach ($csv as $v) {
            $habits = [
                'meditation' => [
                    'id' => 15,
                    'hour' => '14:00:00'
                ],
                'sport' => [
                    'id' => 16,
                    'hour' => '15:00:00'
                ],
                'no_m' => [
                    'id' => 17,
                    'hour' => '23:00:00'
                ],
                'kg' => [
                    'id' => 18,
                    'hour' => '12:00:00'
                ],
            ];

            $value = 0;

            foreach ($habits as $k2 => $v2) {
                switch ($k2) {
                    case 'meditation':
                        $value = (int)$v['3'];
                        break;
                    case 'sport':
                        $value = $v['2'];
                        break;
                    case 'no_m':
                        $value = $v['4'] == 'No' ? 0 : 1;
                        break;
                    case 'kg':
                        $value = (int)$v['5'];
                        break;
                }

                $insert = [
                    'habit_id' => $v2['id'],
                    'date' => date('Y-m-d ' . $v2['hour'], strtotime($v[1])),
                    'value' => $value
                ];
                Tracker::create($insert);
            }
        }

        exit;
    }
}