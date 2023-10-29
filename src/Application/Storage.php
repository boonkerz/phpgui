<?php
declare(strict_types=1);

namespace PHPGui\Application;

use Symfony\Component\Filesystem\Path;

class Storage
{

    public function saveModel(object $model): void
    {

    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    public function loadModel(string $className): object
    {
        dump($this->getLocalAppData());
    }


    private function getLocalAppData() {
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