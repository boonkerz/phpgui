<?php

namespace PHPGui\Driver\SDL\Event;

use PHPGui\Event\Event;
use PHPGui\Shared\TypedCollection;

final class EventCollection extends TypedCollection
{
    protected function type(): string
    {
        return Event::class;
    }
}