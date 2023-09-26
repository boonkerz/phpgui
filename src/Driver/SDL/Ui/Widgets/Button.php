<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class Button extends Base implements Widget, Element
{

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\Button|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {

        $buttonStyle = $actWidget->getStateStyle($actWidget->getState());

        $f = $this->SDL_TTF->TTF_OpenFont($buttonStyle->getFont()->getFont(), $buttonStyle->getFont()->getSize());

        $rectFont = $this->SDL->new('SDL_Rect');
        $rectFont->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding();
        $rectFont->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);
        $rectFont->w = 20;
        $rectFont->h = 20;

        $colorFont =  $this->SDL_TTF->new('SDL_Color');
        $colorFont->r = 0;
        $colorFont->g = 0;
        $colorFont->b = 0;
        $colorFont->a = 255;


        $d = $this->SDL_TTF->TTF_RenderText_Blended($f, $actWidget->text, $colorFont);
        $texture = $this->SDL->SDL_CreateTextureFromSurface($this->window->getRenderPtr(), $this->SDL->cast('SDL_Surface*', $d));

        $this->SDL_TTF->TTF_SizeText($f, $actWidget->text, \FFI::addr($rectFont->w), \FFI::addr($rectFont->h));

        $rectButton = $this->SDL->new('SDL_Rect');
        $rectButton->x = $availableViewPort->x + $actWidget->getMargin() ;
        $rectButton->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP);
        $rectButton->w = $rectFont->w + $actWidget->getPadding() + $actWidget->getPadding(Pos::RIGHT);
        $rectButton->h = $rectFont->h + $actWidget->getPadding(Pos::TOP) + $actWidget->getPadding(Pos::BOTTOM);

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP) {
            if( $rectButton->x <= $this->window->getEvent()->x && $this->window->getEvent()->x <= $rectButton->x + $rectButton->w && $rectButton->y <= $this->window->getEvent()->y && $this->window->getEvent()->y <= $rectButton->y + $rectButton->h ) {
                $actWidget->onClick();
            }
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEMOVE) {
            if( $rectButton->x <= $this->window->getEvent()->x && $this->window->getEvent()->x <= $rectButton->x + $rectButton->w && $rectButton->y <= $this->window->getEvent()->y && $this->window->getEvent()->y <= $rectButton->y + $rectButton->h ) {
                $actWidget->setState(State::HOVER);
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        $this->SDL->roundedBoxRGBA(
            $this->window->getRenderPtr(),
            $rectButton->x, $rectButton->y, $rectButton->x + $rectButton->w,  $rectButton->y + $rectButton->h, 2,
            $buttonStyle->getBackgroundColor()->getR(), $buttonStyle->getBackgroundColor()->getG(), $buttonStyle->getBackgroundColor()->getB(), $buttonStyle->getBackgroundColor()->getA());

        $this->SDL->roundedRectangleRGBA($this->window->getRenderPtr(), $rectButton->x, $rectButton->y, $rectButton->x + $rectButton->w,  $rectButton->y + $rectButton->h, 2, 128, 128, 128, 255);
        $this->SDL->SDL_RenderCopy($this->window->getRenderPtr(), $texture, null, \FFI::addr($rectFont));

        $this->SDL_TTF->TTF_CloseFont($f);
        $this->SDL->SDL_DestroyTexture($texture);
        $this->SDL->SDL_FreeSurface($this->window->getSDL()->cast('SDL_Surface*', $d));

        return new Size(
            $rectButton->w + $actWidget->getMargin() + $actWidget->getMargin(Pos::RIGHT),
            $rectButton->h + $actWidget->getMargin(Pos::TOP) + $actWidget->getMargin(Pos::BOTTOM));
    }
}