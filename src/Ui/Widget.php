<?php

namespace PHPGui\Ui;

use PHPGui\Ui\Style\Style;

abstract class Widget
{
    private int $handleId = 0;

    private Style $style;

    public function getHandleId(): int
    {
        return $this->handleId;
    }

    public function setHandleId(int $handleId): void
    {
        $this->handleId = $handleId;
    }

    abstract public function getAvailableSize(): Size;
}