<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use FFI\CData;
use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Internal\SDL;
use PHPGui\Driver\SDL\Internal\SDL_TTF;
use PHPGui\Driver\SDL\Internal\TTF\Style;
use PHPGui\Driver\SDL\Renderer\Border;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class Label extends Base implements Widget, Element
{
    use Border;

    private ?CData $font = null;
    private ?CData $surface = null;
    private ?CData $texture = null;
    private ?CData $rect = null;

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\Label|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $renderClip = null;
        if($this->getClip()
            && ($this->getClip()->y + $this->getClip()->height < $availableViewPort->y||
                $this->getClip()->y > $availableViewPort->y + $availableViewPort->height
            )) {
            return new Size(
                $actWidget->getWidth() + $actWidget->getMargin() + $actWidget->getMargin(Pos::RIGHT),
                $actWidget->getHeight() + $actWidget->getMargin(Pos::TOP) + $actWidget->getMargin(Pos::BOTTOM));
        }

        $buttonStyle = $actWidget->getStateStyle($actWidget->getState());
        if(!$this->font) {
            $this->font = $this->SDL_TTF->TTF_OpenFont($buttonStyle->getFont()->getFont(), $buttonStyle->getFont()->getSize());

            $colorFont = $this->SDL_TTF->new('SDL_Color');
            $colorFont->r = 0;
            $colorFont->g = 0;
            $colorFont->b = 0;
            $colorFont->a = 255;
            //$this->SDL_TTF->TTF_SetFontStyle($f, Style::TTF_STYLE_BOLD|Style::TTF_STYLE_ITALIC);
            $this->surface = $this->SDL_TTF->TTF_RenderText_Blended($this->font, $actWidget->getText(), $colorFont);
            $this->texture = $this->SDL->SDL_CreateTextureFromSurface($this->window->getRenderPtr(), $this->SDL->cast('SDL_Surface*', $this->surface));
        }

        $this->rect = $this->SDL->new('SDL_Rect');
        $this->rect->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding();
        $this->rect->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);
        $this->rect->w = 200;
        $this->rect->h = 200;

        $this->SDL_TTF->TTF_SizeText($this->font, $actWidget->getText(), \FFI::addr($this->rect->w), \FFI::addr($this->rect->h));

        if($this->getClip()
            && $this->getClip()->y > $availableViewPort->y + $this->rect->h
            ) {
            return new Size(
                $actWidget->getWidth() + $actWidget->getMargin() + $actWidget->getMargin(Pos::RIGHT),
                $actWidget->getHeight() + $actWidget->getMargin(Pos::TOP) + $actWidget->getMargin(Pos::BOTTOM));
        }

        $this->rect->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding();
        $this->rect->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);

        if($this->getClip()
            && ($this->getClip()->y + $this->getClip()->height < $this->rect->y + $this->rect->h
            )) {
            $renderClip = $this->SDL->new('SDL_Rect');
            $renderClip->x = 0;
            $renderClip->y = 0;
            $renderClip->w = $this->rect->w;
            $renderClip->h = $this->getClip()->y + $this->getClip()->height - ($availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP));
            $this->rect->h = $renderClip->h ;
        }

        if($this->getClip()
            && ($this->getClip()->y > $this->rect->y)) {
            $renderClip = $this->SDL->new('SDL_Rect');
            $renderClip->x = 0;
            $renderClip->w = $this->rect->w;
            $renderClip->h = $this->rect->h + $this->rect->y - $this->getClip()->y;
            $renderClip->y = $this->rect->h - $renderClip->h;
            $this->rect->h = $renderClip->h;
            $this->rect->y = $this->rect->y + $renderClip->y;
        }

        $this->SDL->SDL_RenderCopy($this->window->getRenderPtr(), $this->texture, $renderClip != null? \FFI::addr($renderClip) :null , \FFI::addr($this->rect));

        return new Size(
            $actWidget->getWidth() + $actWidget->getMargin() + $actWidget->getMargin(Pos::RIGHT),
            $actWidget->getHeight() + $actWidget->getMargin(Pos::TOP) + $actWidget->getMargin(Pos::BOTTOM));
    }

    public function __destruct()
    {
        $this->SDL_TTF->TTF_CloseFont($this->font);
        $this->SDL->SDL_DestroyTexture($this->texture);
        $this->SDL->SDL_FreeSurface($this->SDL->cast('SDL_Surface*', $this->surface));
    }
}