<?php

namespace PHPGui\Event;

class WindowCloseEvent extends Event
{

    public function __construct(EventType $type = EventType::NOOP, private int $windowID = 0)
    {
        parent::__construct($type);
    }

    public function getWindowID(): int
    {
        return $this->windowID;
    }
}