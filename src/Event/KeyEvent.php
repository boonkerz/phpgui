<?php

namespace PHPGui\Event;

use PHPGui\Keyboard\Key;

class KeyEvent extends Event
{

    private Key $keyCode;

    private bool $repeat = false;

    public function __construct(EventType $type = EventType::KEYDOWN, Key $keyCode = Key::LEFT, bool $repeat = false)
    {
        parent::__construct($type);
    }

    public function getKeyCode(): Key
    {
        return $this->keyCode;
    }

    public function setKeyCode(Key $keyCode): void
    {
        $this->keyCode = $keyCode;
    }

    public function isRepeat(): bool
    {
        return $this->repeat;
    }

    public function setRepeat(bool $repeat): void
    {
        $this->repeat = $repeat;
    }
}