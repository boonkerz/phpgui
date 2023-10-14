<?php

namespace PHPGui\Ui\Widgets;

use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Style\Border;
use PHPGui\Ui\Style\Color;
use PHPGui\Ui\Style\Font;
use PHPGui\Ui\Style\Style;

class TextEdit extends Base
{
    private int $cursorIndex;
    private int $scrollIndex;
    public string $value = "";

    private int $width = 200;

    private int $height = 25;

    public function __construct(string $value = "")
    {
        $this->setState(State::NORMAL);
        $this->addStateStyle(State::NORMAL, (new Style())->setBackgroundColor(new Color(255,255,255,255))->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::HOVER, (new Style())->setBackgroundColor(new Color(255,255,255,255))->setBorder((new Border())->setAll(2, new Color(136,23,152,255)))->setFont((new Font())->setSize(14)));

        $this->cursorIndex = strlen($value);
        $this->value = $value;
        $this->scrollIndex = 0;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $text): self
    {
        $this->value = $text;
        return $this;
    }

    public function onClick(): void
    {
        $this->state = State::FOCUS;
    }

    public function onBackSpace()
    {
        $this->value = substr($this->value, 0, strlen($this->value) - 1);
    }

    public function getCursorIndex(): int
    {
        return $this->cursorIndex;
    }

    public function setCursorIndex(int $cursorIndex): void
    {
        $this->cursorIndex = $cursorIndex;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;
        return $this;
    }
}