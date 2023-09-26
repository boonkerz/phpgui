<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Renderer\Clip;
use PHPGui\Driver\SDL\Renderer\Elements;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Enum\AlignType;
use PHPGui\Ui\Enum\StackpanelMode;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class StackPanel extends Base implements Widget, Element
{
    private array $mouseMovementPanel = ['start' => [0,0], 'to' => [0,0], 'end' => [0,0]];
    private array $panelSize = [0,0];
    private bool $dragMode = false;

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\StackPanel|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        return match ($actWidget->getMode()) {
            StackpanelMode::STACK => $this->renderStackMode($this->window, $availableViewPort, $actWidget),
            Default => $this->renderNormalMode($this->window, $availableViewPort, $actWidget)
        };
    }

    private function renderStackMode(Window $window, ViewPort $availableViewPort, \PHPGui\Ui\Widgets\StackPanel|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $this->panelSize = [0, 0];
        $originalWidth = $availableViewPort->width;
        $originalHeight = $availableViewPort->height;
        $originalY = $availableViewPort->y;
        $originalX = $availableViewPort->x;

        $clipRect = new Clip($originalX, $originalY, $originalWidth, $originalHeight);

        if($this->mouseMovementPanel['to'][1] <= 0) {
            $availableViewPort->y += $this->mouseMovementPanel['to'][1];
        }

        foreach($actWidget->getWidgets() as $widget) {

            $renderSize = Elements::renderElements($window, $availableViewPort, $widget, $clipRect);

            if($actWidget->getAlign() === AlignType::HORIZONTAL) {
                $availableViewPort->x = $availableViewPort->x + $renderSize->width;
                $availableViewPort->y = $originalY;
            }else{
                $availableViewPort->y = $availableViewPort->y + $renderSize->height;
                $availableViewPort->x = $originalX;
            }

            $this->panelSize = [$this->panelSize[0] + $renderSize->width, $this->panelSize[1] + $renderSize->height];
        }
        $availableViewPort->width = $originalWidth;
        $availableViewPort->height = $originalHeight;

        if($window->getEvent() && $window->getEvent()->getType() === EventType::MOUSEBUTTON_DOWN) {
            if( $originalX + $originalWidth - 10 <= $window->getEvent()->x &&
                $window->getEvent()->x <= $originalX + $originalWidth &&
                $originalY <= $window->getEvent()->y &&
                $window->getEvent()->y <= $originalY + $originalHeight ) {
                $this->dragMode = true;
                $this->mouseMovementPanel['start'] = [$window->getEvent()->x, $window->getEvent()->y];
            }
        }
        if($this->dragMode && $window->getEvent() && $window->getEvent()->getType() === EventType::MOUSEMOVE) {
            $this->handleDragMove($originalHeight);
        }
        if($window->getEvent() && $window->getEvent()->getType() === EventType::MOUSEBUTTON_UP) {
            $this->dragMode = false;
            $this->mouseMovementPanel['end'] = $this->mouseMovementPanel['to'];
        }

        $window->getSDL()->roundedRectangleRGBA($window->getRenderPtr(), $originalX, $originalY, $originalX + $originalWidth,  $originalY + $originalHeight, 2, 128, 128, 128, 255);

        if($this->panelSize[1] > $originalHeight && $actWidget->getAlign() === AlignType::VERTICAL) {
            $this->drawVScrollBar($originalX, $originalY, $originalWidth, $originalHeight);
        }else{
            $this->mouseMovementPanel['to'] = [0,0];
            $this->mouseMovementPanel['start'] = [0,0];
            $this->mouseMovementPanel['end'] = [0,0];
        }

        return new Size($originalWidth, $originalHeight);
    }

    private function handleDragMove($originalHeight): void
    {
        $this->mouseMovementPanel['to'] = [
            $this->mouseMovementPanel['end'][0] + $this->window->getEvent()->x - $this->mouseMovementPanel['start'][0],
            $this->mouseMovementPanel['end'][1] + (($this->window->getEvent()->y)*-1) + $this->mouseMovementPanel['start'][1]
        ];
        if($this->mouseMovementPanel['to'][1] >= 0) {
            $this->mouseMovementPanel['start'][1] = $this->window->getEvent()->y;
            $this->mouseMovementPanel['to'] = [$this->mouseMovementPanel['end'][0] + $this->window->getEvent()->x - $this->mouseMovementPanel['start'][0],
                $this->mouseMovementPanel['end'][1] + $this->window->getEvent()->y - $this->mouseMovementPanel['start'][1]];
        }

        if($this->mouseMovementPanel['to'][1] < $originalHeight - $this->panelSize[1]) {
            $this->mouseMovementPanel['to'] = [$this->mouseMovementPanel['end'][0] + $this->window->getEvent()->x - $this->mouseMovementPanel['start'][0],
                $originalHeight - $this->panelSize[1]];

        }
    }

    private function drawVScrollBar($originalX, $originalY, $originalWidth, $originalHeight): void
    {
        //Background
        $this->SDL->boxRGBA($this->window->getRenderPtr(), $originalX + $originalWidth-10, $originalY, $originalX + $originalWidth - 1,  $originalY + $originalHeight, 211, 211, 211, 100);
        //Slider
        $ohP = ($originalHeight / 100);
        $ratio = ($originalHeight / $this->panelSize[1]);

        $sliderHeight = (int)($ratio*100 * $ohP);
        $this->SDL->boxRGBA($this->window->getRenderPtr(), $originalX + $originalWidth-10, $originalY + (int)($this->mouseMovementPanel['to'][1]*$ratio*-1), $originalX + $originalWidth - 1,  $originalY + $sliderHeight + (int)($this->mouseMovementPanel['to'][1]*$ratio*-1), 211, 211, 211, 255);

    }

    private function renderNormalMode(Window $window, ViewPort $availableViewPort, \PHPGui\Ui\Widgets\StackPanel|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $originalWidth = $availableViewPort->width;
        $originalHeight = $availableViewPort->height;
        $originalY = $availableViewPort->y;
        $originalX = $availableViewPort->x;

        $i = 0;

        foreach($actWidget->getWidgets() as $widget) {

            if($actWidget->getAlign() === AlignType::HORIZONTAL) {
                $availableViewPort->width = $actWidget->getColumnSize($i, $originalWidth);
            }else{
                $availableViewPort->height = $actWidget->getColumnSize($i, $originalHeight);
            }

            $renderSize = Elements::renderElements($window, $availableViewPort, $widget);

            $i++;

            if($actWidget->getAlign() === AlignType::HORIZONTAL) {
                $availableViewPort->x = $availableViewPort->x + $availableViewPort->width;
                $availableViewPort->y = $originalY;
            }else{
                $availableViewPort->y = $availableViewPort->y + $availableViewPort->height;
                $availableViewPort->x = $originalX;
            }
        }
        $availableViewPort->width = $originalWidth;
        $availableViewPort->height = $originalHeight;

        $window->getSDL()->roundedRectangleRGBA($window->getRenderPtr(), $originalX, $originalY, $originalX + $originalWidth,  $originalY + $originalHeight, 2, 128, 128, 128, 255);

        return new Size($originalWidth, $originalHeight);
    }
}