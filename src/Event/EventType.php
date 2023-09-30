<?php

namespace PHPGui\Event;

enum EventType: int
{
    case NOOP = 0;
    case QUIT = 1;
    case CLOSE_WINDOW = 2;
    case WINDOW_FOCUS_LOST = 1000;
    case WINDOW_FOCUS_GAINED = 1001;
    case WINDOW_RESIZED = 1002;
    case WINDOW_CLOSE = 1003;

    case MOUSEBUTTON_DOWN = 2000;
    case MOUSEBUTTON_UP = 2001;
    case MOUSEMOVE = 2002;

    case TEXTINPUT = 3000;
    case KEYUP = 3001;
    case KEYDOWN = 3002;

}
