<?php

namespace PHPGui\Ui\Widgets;

use PHPGui\Interface\Ui\Widget;

class Col extends Base implements \PHPGui\Interface\Ui\Widget
{
    private Collection $widgets;

    public function __construct(array $widgets = [])
    {
        $this->widgets = new Collection();
        foreach($widgets as $widget) {
            $this->widgets->add($widget);
        }
    }

    public function addWidget(Widget $widget): self
    {
        $this->widgets->add($widget);
        return $this;
    }

    public function clearWidgets(): void
    {
        $this->widgets = new Collection();
    }
}