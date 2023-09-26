<?php

declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal\Kernel;
enum WindowEvent: int
{
    case SDL_WINDOWEVENT_NONE         = 0;
    case SDL_WINDOWEVENT_SHOWN        = 1;
    case SDL_WINDOWEVENT_HIDDEN       = 2;
    case SDL_WINDOWEVENT_EXPOSED      = 3;
    case SDL_WINDOWEVENT_MOVED        = 4;
    case SDL_WINDOWEVENT_RESIZED      = 5;
    case SDL_WINDOWEVENT_SIZE_CHANGED = 6;
    case SDL_WINDOWEVENT_MINIMIZED    = 7;
    case SDL_WINDOWEVENT_MAXIMIZED    = 8;
    case SDL_WINDOWEVENT_RESTORED     = 9;
    case SDL_WINDOWEVENT_ENTER        = 10;
    case SDL_WINDOWEVENT_LEAVE        = 11;
    case SDL_WINDOWEVENT_FOCUS_GAINED = 12;
    case SDL_WINDOWEVENT_FOCUS_LOST   = 13;
    case SDL_WINDOWEVENT_CLOSE        = 14;
    /**
     * @since SDL 2.0.5
     */
    case SDL_WINDOWEVENT_TAKE_FOCUS   = 15;

    /**
     * @since SDL 2.0.5
     */
    case SDL_WINDOWEVENT_HIT_TEST     = 16;
}
