<?php

namespace PHPGui\Driver\SDL\Renderer;

use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Ui\Trait\Position;
use PHPGui\Ui\Trait\Size;

class Element implements \PHPGui\Interface\Driver\Renderer\Element
{
    use Position;
    use Size;

    private Widget $sdlElement;


    public function getSdlElement(): Widget
    {
        return $this->sdlElement;
    }

    public function setSdlElement(Widget $sdlElement): void
    {
        $this->sdlElement = $sdlElement;
    }
}