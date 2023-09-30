<?php

namespace PHPGui\Driver\SDL\Renderer;

use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class Elements
{

    public  static function renderElements(Window $window, ViewPort $availableViewPort, \PHPGui\Interface\Ui\Widget $actWidget, ?Clip $clip = null): Size
    {
        if(!$actWidget->getRenderElement()) {
            $actWidget->setRenderElement(match(get_class($actWidget)) {
                \PHPGui\Ui\Widgets\Label::class => new \PHPGui\Driver\SDL\Ui\Widgets\Label($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\FPS::class => new \PHPGui\Driver\SDL\Ui\Widgets\FPS($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\Button::class => new \PHPGui\Driver\SDL\Ui\Widgets\Button($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\StackPanel::class => new \PHPGui\Driver\SDL\Ui\Widgets\StackPanel($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\TextEdit::class => new \PHPGui\Driver\SDL\Ui\Widgets\TextEdit($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\TabPanel::class => new \PHPGui\Driver\SDL\Ui\Widgets\TabPanel($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\MenuBar::class => new \PHPGui\Driver\SDL\Ui\Widgets\MenuBar($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\StatusBar::class => new \PHPGui\Driver\SDL\Ui\Widgets\MenuBar($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\Menu\MenuItem::class => new \PHPGui\Driver\SDL\Ui\Widgets\Menu\MenuItem($window->getSDL(), $window->getSDL_TTF(), $window),
                \PHPGui\Ui\Widgets\Menu\Separator::class => new \PHPGui\Driver\SDL\Ui\Widgets\Menu\Separator($window->getSDL(), $window->getSDL_TTF(), $window),
            });
        }
        $actWidget->getRenderElement()->setClip($clip);
        return $actWidget->getRenderElement()->renderUi($availableViewPort, $actWidget);
    }

}