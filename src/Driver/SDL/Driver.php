<?php

namespace PHPGui\Driver\SDL;

use PHPGui\Driver\SDL\Internal\Kernel\WindowEvent;
use PHPGui\Driver\SDL\Internal\SDL;
use PHPGui\Driver\SDL\Internal\SDL_TTF;
use PHPGui\Driver\SDL\Renderer\Elements;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Event\Event;
use PHPGui\Event\EventType;
use PHPGui\Event\KeyEvent;
use PHPGui\Event\MoveDownEvent;
use PHPGui\Event\MoveEvent;
use PHPGui\Event\MoveUpEvent;
use PHPGui\Event\ResizeEvent;
use PHPGui\Event\TextInputEvent;
use PHPGui\Event\WindowCloseEvent;
use PHPGui\Interface\Ui\Widget;
use PHPGui\Keyboard\Key;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;
use PHPGui\Ui\Widgets\Base;
use PHPGui\Ui\Widgets\Button;
use PHPGui\Ui\Widgets\Label;
use PHPGui\Ui\Widgets\StackPanel;
use PHPGui\Ui\Widgets\TextEdit;
use function Sodium\add;

class Driver implements \PHPGui\Interface\Driver\Driver
{
    private SDL $sdl;
    private ?\FFI\CData $event;
    protected \FFI\CData $eventPtr;
    private SDL_TTF $sdl_ttf;

    private array $windows;

    public function __construct(SDL $sdl, SDL_TTF $sdl_ttf)
    {
        $this->sdl = $sdl;
        $this->sdl_ttf = $sdl_ttf;
    }

    public function boot(): void
    {
    }

    public function free(): void
    {
        \FFI::free($this->eventPtr);
        \FFI::free(\FFI::addr($this->event));
    }

    public function initEventSystem(): void
    {
        $this->event = $this->sdl->new('SDL_Event', false);
        $this->eventPtr = \FFI::addr($this->event);
    }

    public function pollEvent(): Event
    {
        $this->sdl->SDL_PollEvent($this->eventPtr);
        return match($this->event->type) {
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_QUIT => new Event(\PHPGui\Event\EventType::QUIT),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_MOUSEBUTTONDOWN => $this->handleMouseDown(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_MOUSEMOTION => $this->handleMouseMove(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_MOUSEBUTTONUP => $this->handleMouseUp(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_WINDOWEVENT => $this->handleWindowEvent(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_TEXTINPUT => $this->handleTextInput(),
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_KEYDOWN,
            \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_KEYUP => $this->handleKeyUp(),
            default => new Event(\PHPGui\Event\EventType::NOOP)
        };
    }



    public function handleWindowEvent(): Event
    {
        if($this->event->window->event == WindowEvent::SDL_WINDOWEVENT_SIZE_CHANGED->value) {
            $this->windows[(int)$this->event->window->windowID]->setViewPortWidth($this->event->window->data1);
            $this->windows[(int)$this->event->window->windowID]->setViewPortHeight($this->event->window->data2);
            return new ResizeEvent(type: EventType::WINDOW_RESIZED, width: (int)$this->event->window->data1, height: (int)$this->event->window->data2);
        }
        if($this->event->window->event == WindowEvent::SDL_WINDOWEVENT_CLOSE->value) {
            $this->windows[$this->event->window->windowID]->close();
            return new WindowCloseEvent(EventType::WINDOW_CLOSE, $this->event->window->windowID);
        }
        return new Event(type: EventType::NOOP);
    }

    public function quit(): void
    {
        $this->sdl->SDL_Quit();
        $this->free();
    }

    public function close(\PHPGui\Ui\Window $window): void
    {
        if($window->getHandleId()) {
            $this->windows[$window->getHandleId()]->close();
        }
    }

    public function show(\PHPGui\Ui\Window $window): int
    {
        $windowFactory = new Window($this->sdl, $this->sdl_ttf);
        $windowSDL = $windowFactory->createFromWindow($window);
        $this->windows[$windowSDL->getWindowId()] = $windowSDL;
        $window->setViewPortSize(new Size($windowSDL->getViewPortWidth(), $windowSDL->getViewPortHeight()));
        return $windowSDL->getWindowId();

    }

    public function update(\PHPGui\Ui\Window $window): void
    {
        if($window->getHandleId() && $this->windows[$window->getHandleId()]) {
            $this->windows[$window->getHandleId()]->startRender();
            $window->setViewPortSize(new Size($this->windows[$window->getHandleId()]->getViewPortWidth(), $this->windows[$window->getHandleId()]->getViewPortHeight()));
            $this->renderElements($window, new ViewPort(0,0, $window->viewPortSize->width, $window->viewPortSize->height), $window->widget);
            $this->windows[$window->getHandleId()]->endRender();
            $this->windows[$window->getHandleId()]->removeEvent();
        }
    }

    public function renderElements(\PHPGui\Ui\Window $window, ViewPort $availableSize, Widget $widget): void
    {
        if($window->getHandleId()) {

            Elements::renderElements($this->windows[$window->getHandleId()], new ViewPort($availableSize->x, $availableSize->y + 20, $availableSize->width, $availableSize->height - 20), $widget);
            if($window->getMenuBar()) {
                Elements::renderElements($this->windows[$window->getHandleId()], $availableSize, $window->getMenuBar());
            }
            if($window->getStatusBar()) {
                Elements::renderElements($this->windows[$window->getHandleId()], $availableSize, $window->getStatusBar());
            }

        }
    }

    public function handleTextInput(): Event
    {
        $event = new TextInputEvent(text: \FFI::string($this->event->text->text));

        $windowId = $this->event->window->windowID;
        if($this->windows[$windowId]) {
            /** @var Window $sdlWindow */
            $sdlWindow = $this->windows[$windowId];
            $sdlWindow->addEvent($event);

        }
        return $event;
    }

    public function handleKeyUp(): Event
    {
        $event = new KeyEvent();
        if($this->event->key->repeat == 1) {
            $event->setRepeat(true);
        }
        if($this->event->key->type == \PHPGui\Driver\SDL\Internal\Kernel\EventType::SDL_KEYUP) {
            $event->setType(EventType::KEYUP);
        }
        $event->setKeyCode(Key::tryFrom($this->event->key->keysym->scancode));
        $windowId = $this->event->window->windowID;
        if($this->windows[$windowId]) {
            /** @var Window $sdlWindow */
            $sdlWindow = $this->windows[$windowId];
            $sdlWindow->addEvent($event);

        }
        return $event;
    }

    private function handleMouseDown(): Event
    {
        $event = new MoveDownEvent(\PHPGui\Event\EventType::MOUSEBUTTON_DOWN, (int)$this->event->motion->x, (int)$this->event->motion->y);

        $windowId = $this->event->window->windowID;
        if($this->windows[$windowId]) {
            /** @var Window $sdlWindow */
            $sdlWindow = $this->windows[$windowId];
            $sdlWindow->addEvent($event);
        }
        return $event;
    }

    public function handleMouseUp(): MoveUpEvent
    {
        $event = new MoveUpEvent(\PHPGui\Event\EventType::MOUSEBUTTON_UP, (int)$this->event->motion->x, (int)$this->event->motion->y);

        $windowId = $this->event->window->windowID;
        if($this->windows[$windowId]) {
            /** @var Window $sdlWindow */
            $sdlWindow = $this->windows[$windowId];
            $sdlWindow->addEvent($event);

        }
        return $event;
    }

    public function handleMouseMove(): MoveEvent
    {

        $event = new MoveEvent(\PHPGui\Event\EventType::MOUSEMOVE, (int)$this->event->motion->x, (int)$this->event->motion->y);
        $windowId = $this->event->window->windowID;
        if($this->windows[$windowId]) {
            /** @var Window $sdlWindow */
            $sdlWindow = $this->windows[$windowId];
            $sdlWindow->modifyEvent($event);

        }
        return $event;
    }

}