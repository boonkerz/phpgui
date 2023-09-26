<?php

declare(strict_types=1);

namespace PHPGui\Provider;

use PHPGui\Application\Application;
use PHPGui\EventLoop\OrderedEventLoop;
use PHPGui\Interface\Driver\Driver;
use PHPGui\Interface\Driver\Instance;
use PHPGui\Interface\EventLoop\LoopInterface;

class EventLoopServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(LoopInterface::class, function (Application $app): LoopInterface {
            return $this->resolve($app);
        });

        $this->app->alias(LoopInterface::class, 'loop');
    }

    private function resolve(Application $app): LoopInterface
    {
        return new OrderedEventLoop($app->make(Driver::class));
    }
}
