<?php
declare(strict_types=1);

namespace PHPGui\Application;

class Settings
{

    public function save(object $model): void
    {

    }

    public function load(string $class): void
    {

    }


    private function get_application_config_dir() {
        $homeDir = $_SERVER['HOME'] ?? '';

        if(empty($homeDir)) {
            $homeDir = getenv('HOME');
        }

        if(empty($homeDir) && $this->is_windows()) {
            $homeData = $_SERVER['LOCALAPPDATA'] ?? '';

            $homeDir = $homeData;
        }

        if(empty($homeDir) && function_exists('exec')) {
            if($this->is_windows()) {
                $homeDir = exec('echo %userprofile%');
            } else {
                $homeDir = exec('echo ~');
            }
        }

        return $homeDir;
    }

    private function is_windows() {
        return strncasecmp(PHP_OS, "WIN", 3) === 0;
    }
}