<?php

namespace Core\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('active', [$this, 'active']),
        ];
    }

    public function active(string $url): bool
    {
        return $_SERVER['REQUEST_URI'] === $url;
    }
}