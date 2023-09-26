<?php
declare(strict_types=1);

namespace PHPGui\Application;

trait InteractWithProviders
{
    private array $providers = [];

    public function withProvider(string $class): void
    {
        /** @var ServiceProvider $provider */
        $provider = new $class($this);

        if (\method_exists($provider, 'boot')) {
            $this->providers[] = $provider;
        }

        $provider->register();
    }

    public function withProviders(iterable $providers): void
    {
        foreach ($providers as $provider) {
            $this->withProvider($provider);
        }
    }

    private function bootServiceProviders(): void
    {
        while (\count($this->providers) > 0) {
            $provider = \array_shift($this->providers);

            $this->call([$provider, 'boot']);
        }
    }
}
