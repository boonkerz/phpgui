<?php

declare(strict_types=1);

namespace PHPGui\Provider;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

abstract class ServiceProvider extends BaseServiceProvider
{
    protected function config(string $key, $default = null)
    {
        $config = $this->app->make(Repository::class);

        return $config->get($key, $default);
    }
}