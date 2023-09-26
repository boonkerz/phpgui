<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use PHPGui\Driver\SDL\Internal\SDL;
use PHPGui\Driver\SDL\Internal\SDL_TTF;
use PHPGui\Driver\SDL\Renderer\Clip;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Interface\Ui\Widget;

class Base
{
    private Widget $widget;
    private ?Clip $clip = null;

    public function __construct(protected SDL $SDL, protected SDL_TTF $SDL_TTF, protected Window $window)
    {
    }

    /**
     * @return Widget
     */
    public function getWidget(): Widget
    {
        return $this->widget;
    }

    /**
     * @param Widget $widget
     */
    public function setWidget(Widget $widget): void
    {
        $this->widget = $widget;
    }

    public function getClip(): ?Clip
    {
        return $this->clip;
    }

    public function setClip(?Clip $clip): void
    {
        $this->clip = $clip;
    }


}