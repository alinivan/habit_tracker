<?php

namespace Core\Templating;

use App\Interfaces\ViewInterface;
use Core\Twig\AppExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigTemplating implements ViewInterface {

    public Environment $twig;
    public FilesystemLoader $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(PATH_VIEWS);
        $this->twig = new Environment($this->loader);

        $this->twig->addExtension(new AppExtension());
    }

    public function renderView(string $view, array $data = []): string
    {
        if (!file_exists(PATH_VIEWS.'/'.$view)) {
            return 'view not found';
        }
        $template = $this->twig->load($view);
        return $template->render($data);
    }
}