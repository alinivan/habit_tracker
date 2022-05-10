<?php

namespace Core\Templating;

use App\Interfaces\ViewInterface;
use eftec\bladeone\BladeOne;

class BladeTemplating implements ViewInterface
{

    public function renderView(string $view, array $data = []): string
    {
        $blade = new BladeOne(PATH_VIEWS, PATH_CACHE, BladeOne::MODE_DEBUG);
        return $blade->run($view, $data);
    }
}