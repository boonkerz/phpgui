<?php

namespace PHPGui\Ui\Style;

class Style
{
    private Border $border;
    private Color $backgroundColor;
    private Font $font;

    public function __construct()
    {
        $this->border = new Border();
        $this->backgroundColor = new Color();
        $this->font = new Font();
        return $this;
    }

    public function getFont(): Font
    {
        return $this->font;
    }

    public function setFont(Font $font): self
    {
        $this->font = $font;
        return $this;
    }

    public function getBackgroundColor(): Color
    {
        return $this->backgroundColor;
    }

    public function setBackgroundColor(Color $backgroundColor): self
    {
        $this->backgroundColor = $backgroundColor;
        return $this;
    }

    public function getBorder(): Border
    {
        return $this->border;
    }

    public function setBorder(Border $border): self
    {
        $this->border = $border;
        return $this;
    }
}