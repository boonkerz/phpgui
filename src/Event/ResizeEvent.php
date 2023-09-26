<?php

namespace PHPGui\Event;

use PHPGui\Ui\Trait\Size;

class ResizeEvent extends Event
{
    use Size;

    public function __construct(EventType $type = EventType::NOOP, int $width = 0, int $height = 0)
    {
        parent::__construct($type);
        $this->width = $width;
        $this->height = $height;
    }
}