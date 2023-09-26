<?php
namespace PHPGui\Ui;

class ViewPort
{

    public function __construct(public int $x, public int $y, public int $width, public int $height)
    {
    }
}