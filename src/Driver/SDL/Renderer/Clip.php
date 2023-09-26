<?php

namespace PHPGui\Driver\SDL\Renderer;

class Clip
{
    public function __construct(public int $x = 0, public int $y = 0, public int $width = 0, public int $height = 0)
    {
    }
}