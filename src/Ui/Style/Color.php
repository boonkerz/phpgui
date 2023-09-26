<?php

namespace PHPGui\Ui\Style;

class Color
{
    public function __construct(private int $r = 255, private int $g = 255, private int $b = 255, private int $a = 255 )
    {
    }

    public function getR(): int
    {
        return $this->r;
    }

    public function setR(int $r): Color
    {
        $this->r = $r;
        return $this;
    }

    public function getG(): int
    {
        return $this->g;
    }

    public function setG(int $g): Color
    {
        $this->g = $g;
        return $this;
    }

    public function getB(): int
    {
        return $this->b;
    }

    public function setB(int $b): Color
    {
        $this->b = $b;
        return $this;
    }

    public function getA(): int
    {
        return $this->a;
    }

    public function setA(int $a): Color
    {
        $this->a = $a;
        return $this;
    }


}