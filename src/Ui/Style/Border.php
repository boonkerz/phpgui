<?php

namespace PHPGui\Ui\Style;

class Border
{
    private int $widthLeft = 0;
    private int $widthRight = 0;
    private int $widthTop = 0;
    private int $widthBottom = 0;

    private Color $colorLeft;
    private Color $colorRight;
    private Color $colorTop;
    private Color $colorBottom;

    public function __construct()
    {
        $this->colorTop = new Color(0,0,0,255);
        $this->colorRight = new Color(0,0,0,255);
        $this->colorBottom = new Color(0,0,0,255);
        $this->colorLeft = new Color(0,0,0,255);
    }

    public function setAll(int $width = 1, Color $color = (new Color())): self
    {
        $this->widthBottom = $this->widthLeft = $this->widthRight = $this->widthTop = $width;
        $this->colorBottom = $this->colorLeft = $this->colorRight = $this->colorTop = $color;
        return $this;
    }

    public function getWidthLeft(): int
    {
        return $this->widthLeft;
    }

    public function setWidthLeft(int $widthLeft): Border
    {
        $this->widthLeft = $widthLeft;
        return $this;
    }

    public function getWidthRight(): int
    {
        return $this->widthRight;
    }

    public function setWidthRight(int $widthRight): Border
    {
        $this->widthRight = $widthRight;
        return $this;
    }

    public function getWidthTop(): int
    {
        return $this->widthTop;
    }

    public function setWidthTop(int $widthTop): Border
    {
        $this->widthTop = $widthTop;
        return $this;
    }

    public function getWidthBottom(): int
    {
        return $this->widthBottom;
    }

    public function setWidthBottom(int $widthBottom): Border
    {
        $this->widthBottom = $widthBottom;
        return $this;
    }

    public function getColorLeft(): Color
    {
        return $this->colorLeft;
    }

    public function setColorLeft(Color $colorLeft): Border
    {
        $this->colorLeft = $colorLeft;
        return $this;
    }

    public function getColorRight(): Color
    {
        return $this->colorRight;
    }

    public function setColorRight(Color $colorRight): Border
    {
        $this->colorRight = $colorRight;
        return $this;
    }

    public function getColorTop(): Color
    {
        return $this->colorTop;
    }

    public function setColorTop(Color $colorTop): Border
    {
        $this->colorTop = $colorTop;
        return $this;
    }

    public function getColorBottom(): Color
    {
        return $this->colorBottom;
    }

    public function setColorBottom(Color $colorBottom): Border
    {
        $this->colorBottom = $colorBottom;
        return $this;
    }



}