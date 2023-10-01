<?php

namespace PHPGui\Driver\SDL\Ui\Widgets\Menu;

use FFI\CData;
use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Renderer\Elements;
use PHPGui\Driver\SDL\Ui\Widgets\Base;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class MenuItem extends Base implements Widget, Element
{

    private ?CData $font = null;
    private ?CData $surface = null;
    private ?CData $texture = null;
    private ?CData $rect;

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\Menu\MenuItem|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $buttonStyle = $actWidget->getStateStyle($actWidget->getState());

        if(!$this->font) {
            $this->font = $this->SDL_TTF->TTF_OpenFont($buttonStyle->getFont()->getFont(), $buttonStyle->getFont()->getSize());

        }

        $colorFont = $this->SDL_TTF->new('SDL_Color');
        $colorFont->r = $buttonStyle->getFont()->getColor()->getR();
        $colorFont->g = $buttonStyle->getFont()->getColor()->getG();
        $colorFont->b = $buttonStyle->getFont()->getColor()->getB();
        $colorFont->a = $buttonStyle->getFont()->getColor()->getA();
        $this->surface = $this->SDL_TTF->TTF_RenderText_Blended($this->font, $actWidget->getTitle(), $colorFont);
        $this->texture = $this->SDL->SDL_CreateTextureFromSurface($this->window->getRenderPtr(), $this->SDL->cast('SDL_Surface*', $this->surface));


        $this->rect = $this->SDL->new('SDL_Rect');
        $this->rect->x = $availableViewPort->x + 7;
        $this->rect->y = $availableViewPort->y;
        $this->rect->w = 200;
        $this->rect->h = 200;

        $this->SDL_TTF->TTF_SizeText($this->font, $actWidget->getTitle(), \FFI::addr($this->rect->w), \FFI::addr($this->rect->h));

        if($actWidget->getState() == State::FOCUS && $actWidget->getSubMenu()) {

            $this->SDL->boxRGBA($this->window->getRenderPtr(), $availableViewPort->x, $availableViewPort->y + 21,
                $availableViewPort->x + 100, $availableViewPort->y + 20 + ($actWidget->getSubMenu()->reduce(function($sum, $item) { return $sum + $item->getHeight(); },0)),
                242, 242, 242,255);

            $this->SDL->rectangleRGBA($this->window->getRenderPtr(), $availableViewPort->x, $availableViewPort->y + 21,
                $availableViewPort->x + 100, $availableViewPort->y + 20 + ($actWidget->getSubMenu()->reduce(function($sum, $item) { return $sum + $item->getHeight(); },0)),
                217, 217, 217, 255);

            $viewPort = new ViewPort($availableViewPort->x, $availableViewPort->y + 20, 100, 20);
            foreach($actWidget->getSubMenu() as $sub)
            {
                $size = Elements::renderElements($this->window, $viewPort, $sub);
                $viewPort->y += $size->height;
            }

        }

        if($actWidget->getLevel() > 0) {
            $this->handleLevel1($actWidget, $buttonStyle, $availableViewPort);
        }else{
            $this->handleLevel0($actWidget, $buttonStyle, $availableViewPort);
        }

        $this->SDL->SDL_RenderCopy($this->window->getRenderPtr(), $this->texture, null , \FFI::addr($this->rect));
        $this->SDL->SDL_DestroyTexture($this->texture);
        $this->SDL->SDL_FreeSurface($this->SDL->cast('SDL_Surface*', $this->surface));

        return new Size($this->rect->w + 10,$actWidget->getHeight());
    }

    public function handleLevel0($actWidget, $buttonStyle, $availableViewPort): void {
        $this->SDL->boxRGBA($this->window->getRenderPtr(), $availableViewPort->x, $availableViewPort->y,
            $availableViewPort->x + $this->rect->w + 10, $availableViewPort->y + $availableViewPort->height,
            $buttonStyle->getBackgroundColor()->getR(), $buttonStyle->getBackgroundColor()->getG(), $buttonStyle->getBackgroundColor()->getB(), $buttonStyle->getBackgroundColor()->getA()
        );

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEMOVE && $actWidget->getState() !== State::FOCUS) {
            if( $availableViewPort->x <= $this->window->getEvent()->x &&
                $this->window->getEvent()->x <= $availableViewPort->x + $this->rect->w + 10 &&
                $availableViewPort->y <= $this->window->getEvent()->y &&
                $this->window->getEvent()->y <= $availableViewPort->y + $availableViewPort->height) {
                $actWidget->setState(State::HOVER);
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP && $actWidget->getSubMenu()) {
            if( $availableViewPort->x <= $this->window->getEvent()->x &&
                $this->window->getEvent()->x <= $availableViewPort->x + $this->rect->w + 10 &&
                $availableViewPort->y <= $this->window->getEvent()->y &&
                $this->window->getEvent()->y <= $availableViewPort->y + $availableViewPort->height) {
                $actWidget->setState(State::FOCUS);
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP && $actWidget->getSubMenu() == null) {
            if( $availableViewPort->x <= $this->window->getEvent()->x &&
                $this->window->getEvent()->x <= $availableViewPort->x + $this->rect->w + 10 &&
                $availableViewPort->y <= $this->window->getEvent()->y &&
                $this->window->getEvent()->y <= $availableViewPort->y + $availableViewPort->height) {
                $actWidget->onClick();
            }
        }
    }

    public function handleLevel1($actWidget, $buttonStyle, $availableViewPort): void {
        $this->SDL->boxRGBA($this->window->getRenderPtr(), $availableViewPort->x, $availableViewPort->y,
            $availableViewPort->x + $availableViewPort->width - 1, $availableViewPort->y + $availableViewPort->height,
            $buttonStyle->getBackgroundColor()->getR(), $buttonStyle->getBackgroundColor()->getG(), $buttonStyle->getBackgroundColor()->getB(), $buttonStyle->getBackgroundColor()->getA()
        );

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEMOVE && $actWidget->getState() !== State::FOCUS) {
            if( $availableViewPort->x <= $this->window->getEvent()->x &&
                $this->window->getEvent()->x <= $availableViewPort->x + $availableViewPort->width &&
                $availableViewPort->y <= $this->window->getEvent()->y &&
                $this->window->getEvent()->y <= $availableViewPort->y + $availableViewPort->height) {
                $actWidget->setState(State::HOVER);
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP && $actWidget->getSubMenu()) {
            if( $availableViewPort->x <= $this->window->getEvent()->x &&
                $this->window->getEvent()->x <= $availableViewPort->x + $availableViewPort->width &&
                $availableViewPort->y <= $this->window->getEvent()->y &&
                $this->window->getEvent()->y <= $availableViewPort->y + $availableViewPort->height) {
                $actWidget->setState(State::FOCUS);
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP && $actWidget->getSubMenu() == null) {
            if( $availableViewPort->x <= $this->window->getEvent()->x &&
                $this->window->getEvent()->x <= $availableViewPort->x + $availableViewPort->width &&
                $availableViewPort->y <= $this->window->getEvent()->y &&
                $this->window->getEvent()->y <= $availableViewPort->y + $availableViewPort->height) {
                $actWidget->onClick();
            }
        }
    }

    public function __destruct()
    {
        $this->SDL_TTF->TTF_CloseFont($this->font);
    }
}