<?php

namespace PHPGui\Ui;

use PHPGui\Interface\Ui\Widget;
use PHPGui\Ui\Widgets\MenuBar;
use PHPGui\Ui\Widgets\StatusBar;

class Window extends \PHPGui\Ui\Widget
{

    public Widget $widget;

    public ?MenuBar $menuBar = null;

    public ?StatusBar $statusBar = null;


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

    public function setMenuBar(?MenuBar $menu): Window
    {
        $this->menuBar = $menu;
        return $this;
    }

    public function getMenuBar(): ?MenuBar
    {
        return $this->menuBar;
    }

    public function setStatusBar(?StatusBar $statusBar): Window
    {
        $this->statusBar = $statusBar;
        return $this;
    }

    public function getStatusBar(): ?StatusBar
    {
        return $this->statusBar;
    }
}