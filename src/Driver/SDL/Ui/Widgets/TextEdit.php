<?php

namespace PHPGui\Driver\SDL\Ui\Widgets;

use PHPGui\Driver\SDL\Interface\Widget;
use PHPGui\Driver\SDL\Renderer\Border;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Keyboard\Key;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

class TextEdit extends Base implements Widget, Element
{
    use Border;

    private $font = __DIR__ . "/../../../../../app/Resources/Montserrat-Medium.ttf";
    private $textEditIndex = 0;
    private $textScrollIndex = 0;

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\TextEdit|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        $buttonStyle = $actWidget->getStateStyle($actWidget->getState());

        $rectBox = $this->SDL->new('SDL_Rect');
        $rectBox->x = $availableViewPort->x + $actWidget->getMargin();
        $rectBox->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP);
        $rectBox->w = $actWidget->getWidth();
        $rectBox->h = $actWidget->getHeight();

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEBUTTON_UP) {
            if( $rectBox->x <= $this->window->getEvent()->x && $this->window->getEvent()->x <= $rectBox->x + $rectBox->w && $rectBox->y <= $this->window->getEvent()->y && $this->window->getEvent()->y <= $rectBox->y + $rectBox->h ) {
                $actWidget->setState(State::FOCUS);
                $this->textEditIndex = strlen($actWidget->getValue());
                $this->textScrollIndex = 0;
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::MOUSEMOVE && $actWidget->getState() !== State::FOCUS) {
            if( $rectBox->x <= $this->window->getEvent()->x && $this->window->getEvent()->x <= $rectBox->x + $rectBox->w && $rectBox->y <= $this->window->getEvent()->y && $this->window->getEvent()->y <= $rectBox->y + $rectBox->h ) {
                $actWidget->setState(State::HOVER);
            }else{
                $actWidget->setState(State::NORMAL);
            }
        }

        if($actWidget->getState() === State::FOCUS && $this->window->getEvent() && $this->window->getEvent()->getType() === EventType::KEYUP && $this->window->getEvent()->getKeyCode() === Key::BACKSPACE) {
            $actWidget->setValue(substr($actWidget->getValue(), 0, $this->textEditIndex - 1));
            $this->textEditIndex = $this->textEditIndex - 1;
        }

        if($this->window->getEvent() && $this->window->getEvent()->getType() === EventType::TEXTINPUT && $actWidget->getState() === State::FOCUS) {
            $actWidget->setValue($actWidget->getValue() . $this->window->getEvent()->text);
            $this->textEditIndex += strlen($this->window->getEvent()->text);
        }

        $this->SDL->roundedBoxRGBA($this->window->getRenderPtr(), $rectBox->x, $rectBox->y, $rectBox->x + $rectBox->w,  $rectBox->y + $rectBox->h, 0,
            $buttonStyle->getBackgroundColor()->getR(), $buttonStyle->getBackgroundColor()->getG(), $buttonStyle->getBackgroundColor()->getB(), $buttonStyle->getBackgroundColor()->getA());

        $this->SDL->roundedRectangleRGBA($this->window->getRenderPtr(), $rectBox->x, $rectBox->y, $rectBox->x + $rectBox->w,  $rectBox->y + $rectBox->h,
            2, $buttonStyle->getBorder()->getColorLeft()->getR(), $buttonStyle->getBorder()->getColorLeft()->getG(), $buttonStyle->getBorder()->getColorLeft()->getB(), $buttonStyle->getBorder()->getColorLeft()->getA());

        $rectFont = $this->SDL->new('SDL_Rect');
        $rectFont->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding();
        $rectFont->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);
        $rectFont->w = 0;
        $rectFont->h = 0;


        if ($actWidget->getValue() != "") {
            $this->calcScrollIndex($actWidget);
            $f = $this->SDL_TTF->TTF_OpenFont($buttonStyle->getFont()->getFont(), $buttonStyle->getFont()->getSize());

            $colorFont = $this->SDL_TTF->new('SDL_Color');
            $colorFont->r = 0;
            $colorFont->g = 0;
            $colorFont->b = 0;
            $colorFont->a = 255;

            $d = $this->SDL_TTF->TTF_RenderText_Blended($f, substr($actWidget->getValue(), $this->textScrollIndex), $colorFont);
            $texture = $this->SDL->SDL_CreateTextureFromSurface($this->window->getRenderPtr(), $this->SDL->cast('SDL_Surface*', $d));

            $this->SDL_TTF->TTF_SizeText($f, substr($actWidget->getValue(), $this->textScrollIndex), \FFI::addr($rectFont->w), \FFI::addr($rectFont->h));

            $this->SDL->SDL_RenderCopy($this->window->getRenderPtr(), $texture, null, \FFI::addr($rectFont));
        }

        $currentTime = $this->SDL->SDL_GetTicks() / 500;

        if (((int)$currentTime & 1) && $actWidget->getState() == State::FOCUS) {

            $this->SDL->boxRGBA($this->window->getRenderPtr(), $rectBox->x + $actWidget->getPadding(Pos::LEFT) + $rectFont->w, $rectBox->y + $actWidget->getPadding(Pos::TOP), $rectBox->x + $actWidget->getPadding(Pos::LEFT) + $rectFont->w + 1, $rectBox->y + $rectBox->h - 2, 128,128,128,255);
        }

        if($actWidget->getValue() != "") {
            $this->SDL_TTF->TTF_CloseFont($f);
            $this->SDL->SDL_DestroyTexture($texture);
            $this->SDL->SDL_FreeSurface($this->SDL->cast('SDL_Surface*', $d));
        }

        return new Size($rectBox->w, $rectBox->h);
    }

    private function renderText(): void
    {
    }

    private function calcScrollIndex(\PHPGui\Ui\Widgets\TextEdit|\PHPGui\Interface\Ui\Widget $actWidget)
    {
        $fWidth = $this->SDL_TTF->TTF_OpenFont($this->font, 14);
        $rectWidth = $this->SDL->new('SDL_Rect');
        $i = $this->textScrollIndex;
        $success = false;

        do {
            $this->SDL_TTF->TTF_SizeText($fWidth, substr($actWidget->getValue(), $i), \FFI::addr($rectWidth->w), \FFI::addr($rectWidth->h));
            if ($rectWidth->w > $actWidget->getWidth() - $actWidget->getPadding() - $actWidget->getPadding(Pos::RIGHT)) {
                $i++;
            } else {
                $success = true;
            }
        } while (!$success && $i < strlen($actWidget->getValue()));

        if($success) {
            $this->textScrollIndex = $i;
        }

        $this->SDL_TTF->TTF_CloseFont($fWidth);
    }
}