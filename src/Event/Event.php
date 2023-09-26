<?php

namespace PHPGui\Event;

class Event
{
    public function __construct(private EventType $type = EventType::NOOP)
    {
    }

    public function getType(): EventType
    {
        return $this->type;
    }

    public function setType(EventType $type): void
    {
        $this->type = $type;
    }

}