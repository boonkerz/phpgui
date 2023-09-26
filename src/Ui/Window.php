<?php

namespace PHPGui\Ui;

use PHPGui\Interface\Ui\Widget;
use PHPGui\Ui\Style\Padding;

class Window extends \PHPGui\Ui\Widget
{

    public Widget $widget;

    public Size $viewPortSize;

    public function __construct(public readonly string $title,
                                public readonly Size $size,
                                public readonly Position $position)
    {
    }

    public function setWidget(Widget $widget): self
    {
        $this->widget = $widget;

        return $this;
    }

    public function getAvailableSize(): Size
    {
        return $this->viewPortSize;
    }

    public function getViewPortSize(): Size
    {
        return $this->viewPortSize;
    }

    public function setViewPortSize(Size $viewPortSize): void
    {
        $this->viewPortSize = $viewPortSize;
    }
}