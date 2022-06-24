<?php

namespace Core\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ViewManager implements ViewInterface {

    public Environment $twig;
    public FilesystemLoader $loader;

    public function __construct()
    {
        $this->loader = new FilesystemLoader(PATH_VIEWS);
        $this->twig = new Environment($this->loader);

        $this->twig->addExtension(new ViewExtension());
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