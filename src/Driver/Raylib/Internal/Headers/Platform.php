<?php

/**
 * This file is part of FFI package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal\Headers;

enum Platform
{
    case WINDOWS;
    case LINUX;
    case DARWIN;
    case FREEBSD;
}
