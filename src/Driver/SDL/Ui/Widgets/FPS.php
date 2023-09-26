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

class FPS extends Base implements Widget, Element
{
    use Border;

    private ?CData $font = null;
    private int $timer_start = 0;
    private int $frame_count = 0;

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Ui\Widgets\Label|\PHPGui\Interface\Ui\Widget $actWidget): Size
    {
        if($this->getClip()
            && ($this->getClip()->y+ $this->getClip()->height < $availableViewPort->y + $actWidget->getHeight() ||
                $availableViewPort->y < $this->getClip()->y
            )) {
            return new Size(
                $actWidget->getWidth() + $actWidget->getMargin() + $actWidget->getMargin(Pos::RIGHT),
                $actWidget->getHeight() + $actWidget->getMargin(Pos::TOP) + $actWidget->getMargin(Pos::BOTTOM));
        }

        if( $this->timer_start == 0 ) {
            $this->timer_start = $this->SDL->SDL_GetTicks();
        }
        $this->frame_count++;
        $duration = $this->SDL->SDL_GetTicks() - $this->timer_start;

        if ($this->frame_count % 10 == 0) {
            $fps = (float)($this->frame_count / $duration * 1000);
            $actWidget->setText(sprintf( "%0.2f fps", $fps));
        }

        $buttonStyle = $actWidget->getStateStyle($actWidget->getState());
        if(!$this->font) {
            $this->font = $this->SDL_TTF->TTF_OpenFont($buttonStyle->getFont()->getFont(), $buttonStyle->getFont()->getSize());
        }
        $colorFont = $this->SDL_TTF->new('SDL_Color');
        $colorFont->r = 0;
        $colorFont->g = 0;
        $colorFont->b = 0;
        $colorFont->a = 255;
        //$this->SDL_TTF->TTF_SetFontStyle($f, Style::TTF_STYLE_BOLD|Style::TTF_STYLE_ITALIC);
        $d = $this->SDL_TTF->TTF_RenderText_Blended($this->font, $actWidget->getText(), $colorFont);
        $texture = $this->SDL->SDL_CreateTextureFromSurface($this->window->getRenderPtr(), $this->SDL->cast('SDL_Surface*', $d));

        $rect = $this->SDL->new('SDL_Rect');
        $rect->x = $availableViewPort->x + $actWidget->getMargin() + $actWidget->getPadding();
        $rect->y = $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getPadding(Pos::TOP);
        $rect->w = 200;
        $rect->h = 200;

        $this->SDL_TTF->TTF_SizeText($this->font, $actWidget->getText(), \FFI::addr($rect->w), \FFI::addr($rect->h));

        $this->SDL->SDL_RenderCopy($this->window->getRenderPtr(), $texture, null, \FFI::addr($rect));

        //$this->SDL_TTF->TTF_CloseFont($f);
        $this->SDL->SDL_DestroyTexture($texture);
        $this->SDL->SDL_FreeSurface($this->SDL->cast('SDL_Surface*', $d));

        return new Size(
            $actWidget->getWidth() + $actWidget->getMargin() + $actWidget->getMargin(Pos::RIGHT),
            $actWidget->getHeight() + $actWidget->getMargin(Pos::TOP) + $actWidget->getMargin(Pos::BOTTOM));
    }

    public function __destruct()
    {

    }
}