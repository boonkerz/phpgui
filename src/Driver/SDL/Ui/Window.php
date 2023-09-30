<?php

namespace PHPGui\Driver\SDL\Ui;

use FFI\CData;
use PHPGui\Driver\SDL\Event\EventCollection;
use PHPGui\Driver\SDL\Internal\Kernel\WindowFlags;
use PHPGui\Driver\SDL\Internal\SDL;
use PHPGui\Driver\SDL\Internal\SDL_TTF;
use PHPGui\Driver\SDL\Renderer\Element;
use PHPGui\Event\Event;

class Window
{

    private \FFI\CData $windowPtr;

    private \FFI\CData $renderPtr;

    private \FFI\CData $viewPortWidth;
    private \FFI\CData $viewPortHeight;

    private EventCollection $eventStack;

    public function __construct(protected SDL $sdl, protected SDL_TTF $sdl_ttf)
    {
        $this->eventStack = new EventCollection();
    }

    public function getSDL(): SDL
    {
        return $this->sdl;
    }

    public function getSDL_TTF(): SDL_TTF
    {
        return $this->sdl_ttf;
    }
    public function createFromWindow(\PHPGui\Ui\Window $window): self
    {
        $this->windowPtr = $this->sdl->SDL_CreateWindow(
            $window->title,
            $window->position->x,
            $window->position->y,
            $window->size->width,
            $window->size->height,
            WindowFlags::SDL_WINDOW_RESIZABLE | WindowFlags::SDL_WINDOW_ALLOW_HIGHDPI);

        $cdata = $this->sdl->cast('SDL_Window*', $this->windowPtr);
        $this->renderPtr = $this->sdl->SDL_CreateRenderer($cdata, 0, 0);

        $this->viewPortHeight = $this->sdl->new('int');
        $this->viewPortWidth = $this->sdl->new('int');

        $this->sdl->SDL_GetWindowSize($this->windowPtr, \FFI::addr($this->viewPortWidth), \FFI::addr($this->viewPortHeight));
        $this->sdl->SDL_RaiseWindow($this->windowPtr);
        return $this;
    }

    public function startRender(): void
    {
        $this->sdl->SDL_SetRenderDrawColor($this->renderPtr, 255, 255, 255, 255);
        $this->sdl->SDL_RenderClear($this->renderPtr);
        $this->sdl->SDL_GetWindowSize($this->windowPtr, \FFI::addr($this->viewPortWidth), \FFI::addr($this->viewPortHeight));
    }

    public function close(): void
    {
        $this->sdl->SDL_DestroyWindow($this->windowPtr);
    }

    public function endRender(): void
    {
        $this->sdl->SDL_RenderPresent($this->renderPtr);
    }

    public function getWindowId(): int
    {
        return $this->sdl->SDL_GetWindowID($this->windowPtr);
    }

    public function getWindowPtr(): CData
    {
        return $this->windowPtr;
    }

    public function getRenderPtr(): CData
    {
        return $this->renderPtr;
    }

    public function getViewPortWidth(): int
    {
        return $this->viewPortWidth->cdata;
    }

    public function getViewPortHeight(): int
    {
        return $this->viewPortHeight->cdata;
    }

    public function setViewPortHeight(int $viewPortHeight): void
    {
        $this->viewPortHeight->cdata = $viewPortHeight;
    }

    public function setViewPortWidth(int $viewPortWidth): void
    {
        $this->viewPortWidth->cdata = $viewPortWidth;
    }

    public function getEvent(): ?Event
    {
        if($this->eventStack->count() > 0) {
            return $this->eventStack->first();
        }
        return null;
    }

    public function addEvent(?Event $actEvent): void
    {
        $this->eventStack->add($actEvent);
    }

    public function modifyEvent(?Event $event): void
    {
        if($this->eventStack->count() == 0) {
            $this->eventStack->add($event);
            return;
        }

        $this->eventStack->map(function(Event $ev) use ($event) {
            if($ev->getType() == $event->getType()) {
                $ev->x = $event->x;
                $ev->y = $event->y;
            }
            return $ev;
        });
    }

    public function removeEvent(): void
    {
        $this->eventStack->removeFirstItem();
    }
}