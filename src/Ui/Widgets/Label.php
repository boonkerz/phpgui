<?php
declare(strict_types=1);

namespace PHPGui\Ui\Widgets;

use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Style\Font;
use PHPGui\Ui\Style\Style;

class Label extends Base implements \PHPGui\Interface\Ui\Widget
{

    private int $width = 200;

    private int $height = 25;

    public function __construct(private string $text = "Label")
    {
        $this->setState(State::NORMAL);
        $this->addStateStyle(State::NORMAL, (new Style())->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::HOVER, (new Style())->setFont((new Font())->setSize(14)));
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
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