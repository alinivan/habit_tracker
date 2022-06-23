<?php

namespace Core;

use Core\Config\Config;
use Dotenv\Dotenv;

class Application
{
    public function route(): void
    {
        require_once APP_ROOT."/routes/web.php";
    }

    public function run(): void
    {
        $this->route();
    }

    public function init(): self
    {
        $this->startSession();
        $this->errorReporting();
        $this->setTimezone();
        $this->loadConfig();
        $this->loadDotEnv();

        return $this;
    }

    private function startSession(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function errorReporting(): void
    {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    }

    private function setTimezone(): void
    {
        date_default_timezone_set('Europe/Bucharest');
    }

    private function loadConfig(): void
    {
        Config::load();
    }

    private function loadDotEnv(): void
    {
        $dotenv = Dotenv::createImmutable(APP_ROOT);
        $dotenv->load();
    }
}