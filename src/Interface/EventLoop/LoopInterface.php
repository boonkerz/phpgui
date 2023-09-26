<?php

declare(strict_types=1);

namespace PHPGui\Interface\EventLoop;


interface LoopInterface
{
    public const DEFAULT_FRAME_RATE = 60;

    public const DEFAULT_UPDATE_RATE = 60;

    public function run(int $frameRate = self::DEFAULT_FRAME_RATE, int $updateRate = self::DEFAULT_UPDATE_RATE): void;

    public function use(?WorkerInterface $worker): void;

    public function pause(): void;

    public function resume(): void;

    public function stop(): void;
}
