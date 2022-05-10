<?php

namespace Core;

class Application
{

    public function route(): void
    {
        require_once "../routes/web.php";
    }

    public function run(): void
    {
        $this->route();
    }
}