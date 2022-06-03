<?php

namespace Core\Templating;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TwigAppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('active', [$this, 'active']),
            new TwigFunction('sidebar', [$this, 'sidebar'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function active(string $url): bool
    {
        return $_SERVER['REQUEST_URI'] === $url;
    }

    public function sidebar(Environment $twig)
    {
        $menu = [
            [
                'href' => '/dashboard',
                'name' => 'Dashboard'
            ],
            [
                'href' => '/habits',
                'name' => 'Habits'
            ],
            [
                'href' => '/tracker',
                'name' => 'Tracker'
            ],
        ];

        foreach ($menu as &$v) {
            $v['active'] = $_SERVER['REQUEST_URI'] === $v['href'];
        }

        return $twig->render('app/sidebar.html.twig', ['menu' => $menu]);
    }
}