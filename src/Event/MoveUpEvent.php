<?php

namespace PHPGui\Event;

use PHPGui\Ui\Trait\Position;

class MoveUpEvent extends Event
{
    use Position;

    public function __construct(EventType $type = EventType::NOOP, int $x = 0, int $y = 0)
    {
        parent::__construct($type);
        $this->x = $x;
        $this->y = $y;
    }
}