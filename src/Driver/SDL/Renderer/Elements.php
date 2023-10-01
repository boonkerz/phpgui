<?php

namespace PHPGui\Driver\SDL\Renderer;

use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;
use PHPGui\Ui\Widgets\Base;
use PHPGui\Ui\Widgets\FPS;
use PHPGui\Ui\Widgets\Menu\Item;

class Elements
{

    public  static function renderElements(Window $window, ViewPort $availableViewPort, \PHPGui\Interface\Ui\Widget $actWidget, ?Clip $clip = null): Size
    {
        if(!$actWidget->getRenderElement()) {

            $className = get_parent_class($actWidget);
            if($className == Base::class || $className == Item::class || $actWidget::class == FPS::class) {
                $className = $actWidget::class;
            }

            $actWidget->setRenderElement(match($className) {
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
                default => throw new \Exception('Unexpected match value: '.get_parent_class($actWidget)),
            });
        }
        $actWidget->getRenderElement()->setClip($clip);
        return $actWidget->getRenderElement()->renderUi($availableViewPort, $actWidget);
    }

}