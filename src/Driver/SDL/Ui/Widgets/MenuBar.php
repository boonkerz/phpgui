<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Renderer\Elements;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class MenuBar extends Base implements Widget, Element
{


    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\MenuBar|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $orgHeight = $availableViewPort->height;
        $availableViewPort->height = 20;
        $this->SDL->boxRGBA($this->window->getRenderPtr(),
            $availableViewPort->x, $availableViewPort->y,
            $availableViewPort->x + $availableViewPort->width, $availableViewPort->y + 20,
            242, 242, 242,255);

        $this->SDL->hlineRGBA($this->window->getRenderPtr(),
            $availableViewPort->x,$availableViewPort->x + $availableViewPort->width,
             20, 217, 217, 217, 255);

        foreach($actWidget->getMenuItems() as $item) {
            $renderSize = Elements::renderElements($this->window, $availableViewPort, $item);
            $availableViewPort->x += $renderSize->width;
        }
        $availableViewPort->height = $orgHeight;
        return new Size($availableViewPort->width, 20);
    }
}