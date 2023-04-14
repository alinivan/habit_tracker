<?php

namespace Core\View;

use Core\Helpers\Url;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViewExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('active', [$this, 'active']),
            new TwigFunction('url', [$this, 'url']),
            new TwigFunction('sidebar', [$this, 'sidebar'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    public function active(string $url): bool
    {
        $reqUri = Url::getNormalizedRoute($_SERVER['REQUEST_URI'])['uri'];
        return $reqUri === $url;
    }

    public function sidebar(Environment $twig): string
    {
        $menu = [
            [
                'href' => '/dashboard?routine_view',
                'name' => 'Dashboard'
            ],
            [
                'href' => '/habits',
                'name' => 'Habits'
            ],
            [
                'href' => '/categories',
                'name' => 'Categories'
            ],
            [
                'href' => '/tracker',
                'name' => 'Tracker'
            ],
            [
                'href' => '/routine',
                'name' => 'Routine'
            ],
            [
                'href' => '/tasks',
                'name' => 'Tasks'
            ]
        ];

        foreach ($menu as &$v) {
            $v['active'] = $_SERVER['REQUEST_URI'] === $v['href'];
        }

        return $twig->render('app/sidebar.html.twig', ['menu' => $menu]);
    }

    public function url(string $url)
    {
        
    }
}