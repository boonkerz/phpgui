<?php

namespace PHPGui\Driver\Raylib;

use PHPGui\Application\Application;
use PHPGui\Driver\Raylib\Internal\Raylib;
use Shieldon\SimpleCache\Driver\File;

class Provider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $cache = new File([
            'storage' => __DIR__ . '/../../../resources/cache'
        ]);

        $sdl = new Raylib(cache: $cache);

       /*
        $this->app->singleton(Window::class, function (Application $app) use ($sdl, $sdlTtf): Window {
            return new Window($sdl, $sdlTtf);
        });

        $driver = new Driver($sdl, $sdlTtf, $this->app->make(Window::class));
        $this->app->instance(\PHPGui\Interface\Driver\Driver::class, $driver);*/

    }
}