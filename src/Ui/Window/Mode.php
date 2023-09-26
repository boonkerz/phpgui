<?php

declare(strict_types=1);

namespace PHPGui\Ui\Window;

enum Mode: int
{
    case NORMAL = 1;

    case HIDDEN = 2;
    case FULLSCREEN = 3;
    case DESKTOP_FULLSCREEN = 4;
}
