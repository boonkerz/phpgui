<?php

namespace PHPGui\Driver\Raylib;

use PHPGui\Driver\Raylib\Internal\Raylib;
use PHPGui\Event\Event;
use PHPGui\Event\EventType;
use PHPGui\Event\MoveEvent;
use PHPGui\Event\MoveUpEvent;
use PHPGui\Event\ResizeEvent;
use PHPGui\Interface\Ui\Widget;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;
use PHPGui\Ui\Widgets\Button;
use PHPGui\Ui\Widgets\Horizontal;
use PHPGui\Ui\Widgets\Label;

class Driver implements \PHPGui\Interface\Driver\Driver
{

    private Raylib $raylib;
    private array $windows;


    public function __construct(Raylib $raylib)
    {
        $this->raylib = $raylib;
    }

    public function boot(): void
    {
    }

    public function free(): void
    {
       // \FFI::free($this->eventPtr);
    }

    public function initEventSystem(): void
    {
       // $this->event = $this->sdl->new('SDL_Event', false);
       // $this->eventPtr = \FFI::addr($this->event);
    }

    public function pollEvent(): Event
    {
        /*$this->sdl->SDL_PollEvent($this->eventPtr);

        return match($this->event->type) {
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_QUIT => new Event(\PHPGui\Event\EventType::QUIT),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_MOUSEBUTTONDOWN => new Event(\PHPGui\Event\EventType::MOUSEBUTTON_DOWN),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_MOUSEMOTION => $this->handleMouseMove(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_MOUSEBUTTONUP => $this->handleMouseUp(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_WINDOWEVENT => $this->handleWindowEvent(),
            default => new Event(\PHPGui\Event\EventType::NOOP)
        };*/
    }

    public function handleMouseMove(): MoveEvent
    {
        return new MoveEvent(\PHPGui\Event\EventType::MOUSEMOVE, (int)$this->event->motion->x, (int)$this->event->motion->y);
    }

    public function handleMouseUp(): MoveUpEvent
    {
        return new MoveUpEvent(\PHPGui\Event\EventType::MOUSEBUTTON_UP, (int)$this->event->motion->x, (int)$this->event->motion->y);
    }

    public function handleWindowEvent(): Event
    {
        if($this->event->window->event == WindowEvent::SDL_WINDOWEVENT_SIZE_CHANGED->value) {
            $this->windows[(int)$this->event->window->windowID]->setViewPortWidth($this->event->window->data1);
            $this->windows[(int)$this->event->window->windowID]->setViewPortHeight($this->event->window->data2);
            return new ResizeEvent(type: EventType::WINDOW_RESIZED, width: (int)$this->event->window->data1, height: (int)$this->event->window->data2);
        }
        return new Event(type: EventType::NOOP);
    }

    public function quit(): void
    {
        //$this->sdl->SDL_Quit();
    }

    public function show(\PHPGui\Ui\Window $window): int
    {
        $windowSDL = $this->windowFactory->createFromWindow($window);
        $this->windows[$windowSDL->getWindowId()] = $windowSDL;
        $window->setViewPortSize(new Size($windowSDL->getViewPortWidth(), $windowSDL->getViewPortHeight()));
        return $windowSDL->getWindowId();

    }

    public function update(\PHPGui\Ui\Window $window): void
    {
        if($window->getHandleId()) {
            $this->windows[$window->getHandleId()]->startRender();
            $window->setViewPortSize(new Size($this->windows[$window->getHandleId()]->getViewPortWidth(), $this->windows[$window->getHandleId()]->getViewPortHeight()));
            $this->renderElements($window, new ViewPort(0,0, $window->viewPortSize->width, $window->viewPortSize->height), $window->widget);
            $this->windows[$window->getHandleId()]->endRender();
        }
    }

    public function renderElements(\PHPGui\Ui\Window $window, ViewPort $availableSize, Widget $widget): void
    {
        /*
        if($window->getHandleId()) {
            switch (get_class($widget)) {
                case Label::class:
                    \PHPGui\Driver\SDL\Ui\Widgets\Label::renderUi($this->windows[$window->getHandleId()], $availableSize, $widget);
                    break;
                case Button::class:
                    \PHPGui\Driver\SDL\Ui\Widgets\Button::renderUi($this->windows[$window->getHandleId()], $availableSize, $widget);
                    break;
                case Horizontal::class:
                    \PHPGui\Driver\SDL\Ui\Widgets\Horizontal::renderUi($this->windows[$window->getHandleId()], $availableSize, $widget);
                    break;
            }
        }
        */
    }

    public function checkClick(\PHPGui\Ui\Window $window, MoveUpEvent $event): void
    {
        /*if($window->getHandleId()) {
            $sdlWindow = $this->windows[$window->getHandleId()];
            foreach($sdlWindow->getActionViewStack() as $item) {
                if( $item['x1'] <= $event->x && $event->x <= $item['x2'] && $item['y1'] <= $event->y && $event->y <= $item['y2'] ) {
                    if(method_exists($item['element'], 'onClick')) {
                        $item['element']->onClick();
                    }
                }
            }
        }*/
    }
}