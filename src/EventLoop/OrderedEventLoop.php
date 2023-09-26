<?php

declare(strict_types=1);

namespace PHPGui\EventLoop;

use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Driver;
use PHPGui\Provider\Timer;

class OrderedEventLoop extends EventLoop
{
    /**
     * @var Timer
     */
    public Timer $render;

    /**
     * @var Timer
     */
    public Timer $updates;

    public function __construct(Driver $driver)
    {
        parent::__construct($driver);

        $this->render = new Timer(self::DEFAULT_FRAME_RATE);
        $this->updates = new Timer(self::DEFAULT_UPDATE_RATE);
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(int $frameRate, int $updateRate): void
    {
        $this->render->rate($frameRate)->touch();
        $this->updates->rate($updateRate)->touch();

        while ($this->running) {
            $now = \microtime(true);

            if (($delta = $this->updates->next($now)) !== null) {
                $this->update($delta);
            }

            if (($delta = $this->render->next($now)) !== null) {
                $this->render($delta);
            }

            while ($event = $this->driver->pollEvent()) {
                if($event->getType() === EventType::NOOP) break;
                $this->poll($event);
            }
        }
    }
}
