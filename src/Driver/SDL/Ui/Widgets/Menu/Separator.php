<?php

namespace PHPGui\Driver\SDL\Ui\Widgets\Menu;

use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Ui\Widgets\Base;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class Separator extends Base implements Widget, Element
{

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\Menu\Separator|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {

        $this->SDL->hlineRGBA($this->window->getRenderPtr(),
            $availableViewPort->x, $availableViewPort->x + $availableViewPort->width, $availableViewPort->y +5,
            217, 217, 217, 255);

        return new Size($availableViewPort->width, $actWidget->getHeight());
    }
}