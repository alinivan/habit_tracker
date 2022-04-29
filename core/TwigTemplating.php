<?php

namespace Core;

use App\Interfaces\ViewInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigTemplating implements ViewInterface {

    public Environment $twig;
    public FilesystemLoader $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(PATH_VIEWS);
        $this->twig = new Environment($this->loader);
    }

    public function renderView(string $view, array $data = []): string
    {
        if (!file_exists(PATH_VIEWS.'/'.$view)) {
            return 404;
        }
        $template = $this->twig->load($view);
        return $template->render($data);
    }
}