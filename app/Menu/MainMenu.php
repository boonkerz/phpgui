<?php

namespace App\Menu;

use PHPGui\Ui\Widgets\Menu\MenuItem;
use PHPGui\Ui\Widgets\Menu\Separator;
use PHPGui\Ui\Widgets\MenuBar;

class MainMenu extends MenuBar
{
    public MenuItem $exitItem;
    public MenuItem $settingItem;

    public function __construct(array $menuItems = [])
    {
        $this->exitItem = new MenuItem(title: "Exit");
        $this->settingItem = new MenuItem(title: "Settings");

        $items = [
            (new MenuItem(title: "File", subMenu: [
                $this->settingItem,
                (new Separator()),
                $this->exitItem,
            ])),
            (new MenuItem(title: "Module", subMenu: [
                (new MenuItem(title: "Server")),
                (new Separator()),
                (new MenuItem(title: "Notes")),
            ])),

            (new MenuItem(title: "Help", subMenu: [
                (new MenuItem(title: "Help")),
                (new MenuItem(title: "About")),
            ]))
        ];

        parent::__construct($items);
    }
}