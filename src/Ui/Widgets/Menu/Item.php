<?php

namespace PHPGui\Ui\Widgets\Menu;

use PHPGui\Ui\Widgets\Base;

abstract class Item extends Base implements \PHPGui\Interface\Ui\Widget
{

    private int $level = 0;

    private int $height = 20;

    public function setLevel(int $level): Item
    {
        $this->level = $level;
        return $this;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function setHeight(int $height): Item
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }
}