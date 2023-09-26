<?php

namespace PHPGui\Ui\Style;

class Font
{
    public function __construct(
        private string $font = __DIR__ . "/../../Theme/WindowsForms/assets/segoe-ui.ttf",
        private int $size = 10,
        private bool $bold = false,
        private bool $italic = false,
        private bool $underline = false,
        private Color $color = new Color()
    )
    {
    }

    public function getFont(): string
    {
        return $this->font;
    }

    public function setFont(string $font): self
    {
        $this->font = $font;
        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function isBold(): bool
    {
        return $this->bold;
    }

    public function setBold(bool $bold): self
    {
        $this->bold = $bold;
        return $this;
    }
    public function isItalic(): bool
    {
        return $this->italic;
    }

    public function setItalic(bool $italic): self
    {
        $this->italic = $italic;
        return $this;
    }

    public function isUnderline(): bool
    {
        return $this->underline;
    }

    public function setUnderline(bool $underline): self
    {
        $this->underline = $underline;
        return $this;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function setColor(Color $color): self
    {
        $this->color = $color;
        return $this;
    }
}