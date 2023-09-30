<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use FFI\CData;
use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Renderer\Clip;
use PHPGui\Driver\SDL\Renderer\Elements;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Enum\AlignType;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\Enum\StackpanelMode;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;
use PHPGui\Ui\Widgets\TabPanel\Tab;

class TabPanel extends Base implements Widget, Element
{

    private ?CData $font = null;
    private ?CData $surface = null;
    private ?CData $texture = null;
    private ?CData $rect = null;
    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\TabPanel|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $this->renderTabBar($availableViewPort, $actWidget);

        $availableViewPort->y += 20;
        $availableViewPort->height -= 20;

        $this->renderActiveTab($availableViewPort, $actWidget->getActiveTab());

        return new Size($availableViewPort->width, $availableViewPort->height);
    }

    private function renderTabBar(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\TabPanel|\PHPGui\Interface\Ui\Widget $actWidget): void
    {
        $this->SDL->boxRGBA($this->window->getRenderPtr(), $availableViewPort->x + 0, $availableViewPort->y, $availableViewPort->x + $availableViewPort->width, $availableViewPort->y + 20, 211, 211, 211, 255);

        $x = 10;
        $y = 0;

        /** @var Tab $tab */
        foreach ($actWidget->getTabs() as $tab) {

            $buttonStyle = $tab->getStateStyle($tab->getState());
            if($tab->isActive()) {
                $buttonStyle = $tab->getStateStyle(State::ACTIVE);
            }

            $this->font = $this->SDL_TTF->TTF_OpenFont($buttonStyle->getFont()->getFont(), $buttonStyle->getFont()->getSize());

            $colorFont = $this->SDL_TTF->new('SDL_Color');
            $colorFont->r = 0;
            $colorFont->g = 0;
            $colorFont->b = 0;
            $colorFont->a = 255;

            $this->surface = $this->SDL_TTF->TTF_RenderText_Blended($this->font, $tab->getTitle(), $colorFont);
            $this->texture = $this->SDL->SDL_CreateTextureFromSurface($this->window->getRenderPtr(), $this->SDL->cast('SDL_Surface*', $this->surface));

            $this->rect = $this->SDL->new('SDL_Rect');
            $this->rect->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding();
            $this->rect->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);
            $this->rect->w = 200;
            $this->rect->h = 200;

            $this->SDL_TTF->TTF_SizeText($this->font, $tab->getTitle(), \FFI::addr($this->rect->w), \FFI::addr($this->rect->h));

            if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEMOVE) {
                if( $availableViewPort->x + $actWidget->getMargin() + $y <= $this->window->getEvent()->x &&
                    $this->window->getEvent()->x <= $availableViewPort->x + $actWidget->getMargin() + $y + $this->rect->w + 20 &&
                    $availableViewPort->y + $actWidget->getMargin(Pos::TOP) <= $this->window->getEvent()->y &&
                    $this->window->getEvent()->y <= $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + 20 ) {
                    $tab->setState(State::HOVER);
                }else{
                    $tab->setState(State::NORMAL);
                }
            }

            if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP) {
                if( $availableViewPort->x + $actWidget->getMargin() + $y <= $this->window->getEvent()->x &&
                    $this->window->getEvent()->x <= $availableViewPort->x + $actWidget->getMargin() + $y + $this->rect->w + 20 &&
                    $availableViewPort->y + $actWidget->getMargin(Pos::TOP) <= $this->window->getEvent()->y &&
                    $this->window->getEvent()->y <= $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + 20 ) {
                    $actWidget->setActiveTab($tab);
                }
            }

            $this->SDL->boxRGBA(
                $this->window->getRenderPtr(),
                $availableViewPort->x + $actWidget->getMargin() + $y, $availableViewPort->y + $actWidget->getMargin(Pos::TOP), $availableViewPort->x + $actWidget->getMargin() + $y + $this->rect->w + 20,  $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + 20,
                $buttonStyle->getBackgroundColor()->getR(), $buttonStyle->getBackgroundColor()->getG(), $buttonStyle->getBackgroundColor()->getB(), $buttonStyle->getBackgroundColor()->getA());




            $this->rect->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding() + $x;
            $this->rect->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);

            $this->SDL->SDL_RenderCopy($this->window->getRenderPtr(), $this->texture, null , \FFI::addr($this->rect));

            $x += $this->rect->w + 20;
            $y += $this->rect->w + 20;

            $this->SDL_TTF->TTF_CloseFont($this->font);
            $this->SDL->SDL_DestroyTexture($this->texture);
            $this->SDL->SDL_FreeSurface($this->SDL->cast('SDL_Surface*', $this->surface));

        }
    }

    private function renderActiveTab(ViewPort $availableViewPort, Tab $getActiveTab)
    {
        Elements::renderElements($this->window, $availableViewPort, $getActiveTab->getWidget());
    }
}