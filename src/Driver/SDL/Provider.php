<?php

namespace PHPGui\Driver\SDL;

use PHPGui\Application\Application;
use PHPGui\Driver\SDL\Internal\Init;
use PHPGui\Driver\SDL\Internal\SDL;
use PHPGui\Driver\SDL\Internal\SDL_TTF;
use PHPGui\Driver\SDL\Ui\Window;
use Shieldon\SimpleCache\Driver\File;

class Provider extends \Illuminate\Support\ServiceProvider
{
    public function register(): void
    {
        $cache = new File([
            'storage' => __DIR__ . '/../../../resources/cache'
        ]);

        $sdl = new SDL(init: Init::VIDEO | Init::AUDIO, cache: $cache);

        $sdlTtf = new SDL_TTF(sdl: $sdl);

        $this->app->singleton(Window::class, function (Application $app) use ($sdl, $sdlTtf): Window {
            return new Window($sdl, $sdlTtf);
        });

        $driver = new Driver($sdl, $sdlTtf, $this->app->make(Window::class));
        $this->app->instance(\PHPGui\Interface\Driver\Driver::class, $driver);

    }
}