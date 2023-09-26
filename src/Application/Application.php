<?php
declare(strict_types=1);

namespace PHPGui\Application;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Container\Container;
use PHPGui\Interface\Driver\Instance;
use PHPGui\Interface\EventLoop\LoopInterface;
use PHPGui\Provider\ConfigServiceProvider;
use PHPGui\Provider\EventLoopServiceProvider;
use PHPGui\Provider\WindowServiceProvider;

final class Application extends \Illuminate\Container\Container
{

    use InteractWithPaths;
    use InteractWithProviders;
    private const DEFAULT_SERVICE_PROVIDERS = [
        ConfigServiceProvider::class,
        EventLoopServiceProvider::class,
    ];

    public function __construct(private readonly string $rootDirectory)
    {
        $this->bindSelf();
        $this->registerPaths($rootDirectory);
        $this->registerDefaultProviders();
        $this->registerDriver();
    }

    public function init(): Container
    {
    }

    public function run(): void
    {
        $this->bootServiceProviders();

        $loop = $this->make(LoopInterface::class);
        $loop->run();
    }

    private function bindSelf(): void
    {
        $this->instance(self::class, $this);
        $this->alias(self::class, Container::class);
    }

    private function registerDefaultProviders(): void
    {
        $this->withProviders(self::DEFAULT_SERVICE_PROVIDERS);

        $config = $this->make(Repository::class);

        $this->withProviders((array)$config->get('provider', []));
    }

    private function registerDriver(): void
    {
        $config = $this->make(Repository::class);

        if($config->get('renderer.driver', null) === null) {
            die("no driver specified");
        }

        $this->withProvider( $config->get('renderer.driver'));

    }

    private function bootServiceProviders(): void
    {
        while (\count($this->providers) > 0) {
            $provider = \array_shift($this->providers);

            $this->call([$provider, 'boot']);
        }

    }
}