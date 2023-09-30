<?php

namespace PHPGui\Ui\Widgets;

class MenuBar extends Base implements \PHPGui\Interface\Ui\Widget
{
    private \PHPGui\Ui\Widgets\Menu\Collection $menuItems;

    public function __construct(array $menuItems = [])
    {
        $this->menuItems = new \PHPGui\Ui\Widgets\Menu\Collection();
        foreach($menuItems as $menuItem) {
            $menuItem->setLevel(0);
            $this->menuItems->add($menuItem);
        }
    }

    public function getMenuItems(): Menu\Collection
    {
        return $this->menuItems;
    }
}