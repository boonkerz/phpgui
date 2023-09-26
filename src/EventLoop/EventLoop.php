<?php

declare(strict_types=1);

namespace PHPGui\EventLoop;

use PHPGui\Event\Event;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Driver;
use PHPGui\Interface\EventLoop\LoopInterface;
use PHPGui\Interface\EventLoop\WorkerInterface;

abstract class EventLoop implements LoopInterface
{
    protected bool $paused = false;

    /**
     * @var bool
     */
    protected bool $running = false;

    private ?WorkerInterface $worker = null;

    public function __construct(protected Driver $driver)
    {}

    public function use(?WorkerInterface $worker): void
    {
        $this->worker = $worker;
    }

    protected function render(float $delta): void
    {
        if ($this->worker !== null) {
            $this->worker->onRender($delta);
        }
    }

    protected function poll(Event $event): void
    {
        if ($event->getType() === EventType::QUIT) {
            $this->running = false;
        }

        if ($this->worker !== null) {
            $this->worker->onEvent($event);
        }
    }

    protected function update(float $delta): void
    {
        if ($this->worker !== null && $this->paused === false) {
            $this->worker->onUpdate($delta);
        }
    }

    public function pause(): void
    {
        if ($this->paused === false && $this->worker !== null) {
            $this->worker->onPause();
        }

        $this->paused = true;
    }

    public function resume(): void
    {
        if ($this->paused === true && $this->worker !== null) {
            $this->worker->onResume();
        }

        $this->paused = false;
    }

    public function run(int $frameRate = self::DEFAULT_FRAME_RATE, int $updateRate = self::DEFAULT_UPDATE_RATE): void
    {
        $this->paused = false;

        if ($this->running) {
            return;
        }

        $this->driver->initEventSystem();

        try {
            $this->running = true;

            $this->execute($frameRate, $updateRate);
        } finally {
            $this->driver->free();

        }
    }

    abstract protected function execute(int $frameRate, int $updateRate): void;

    public function stop(): void
    {
        $this->running = false;

        $this->driver->quit();
    }
}
