<?php

namespace Core\Config;

class Config
{
    public static function load(): void
    {
        require_once APP_ROOT."/config/config.php";
    }
}