<?php

namespace PHPGui\Ui\Widgets\Menu;

class Separator extends Item
{
    private int $height = 10;

    public function getHeight(): int
    {
        return $this->height;
    }
}