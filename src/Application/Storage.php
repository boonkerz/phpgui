<?php
declare(strict_types=1);

namespace PHPGui\Application;

use Illuminate\Config\Repository;
use PHPGui\Provider\ConfigServiceProvider;
use Symfony\Component\Filesystem\Path;
use Zumba\JsonSerializer\JsonSerializer;

class Storage
{
    public function __construct(private readonly Repository $config, private readonly JsonSerializer $jsonSerializer)
    {
    }

    public function saveModel(object $model): void
    {
        $path = $this->getLocalAppData() . DIRECTORY_SEPARATOR . md5($model::class). '.json';
        if(!file_exists($this->getLocalAppData())) {
            mkdir($this->getLocalAppData(), 0777, true);
        }
        file_put_contents($path, $this->jsonSerializer->serialize($model));
    }

    /**
     * @template T
     * @param class-string<T> $className
     * @return T
     */
    public function loadModel(string $className): object
    {
        $path = $this->getLocalAppData() . DIRECTORY_SEPARATOR . md5($className). '.json';
        if(file_exists($path)) {
            return $this->jsonSerializer->unserialize(file_get_contents($path));
        }else{
            return new $className();
        }
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

        return implode(DIRECTORY_SEPARATOR ,[$homeDir, $this->config->get('app')['appId']]);
    }

    private function is_windows() {
        return strncasecmp(PHP_OS, "WIN", 3) === 0;
    }
}