<?php

declare(strict_types=1);

namespace PHPGui\Interface\EventLoop;

use PHPGui\Event\Event;

interface WorkerInterface
{
    public function onUpdate(float $delta): void;

    public function onRender(float $delta): void;

    public function onEvent(Event $event): void;

    public function onPause(): void;

    public function onResume(): void;
}
