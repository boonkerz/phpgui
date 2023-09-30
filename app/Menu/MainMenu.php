<?php

namespace App\Menu;

use PHPGui\Ui\Widgets\Menu\MenuItem;
use PHPGui\Ui\Widgets\Menu\Separator;
use PHPGui\Ui\Widgets\MenuBar;

class MainMenu
{

    public function buildMainMenu(?\Closure $onExitClick): MenuBar {
        return new MenuBar(menuItems: [
            (new MenuItem(title: "File", subMenu: [
                (new MenuItem(title: "Settings")),
                (new Separator()),
                (new MenuItem(title: "Exit", onClick: $onExitClick)),
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
        ]);
    }

}