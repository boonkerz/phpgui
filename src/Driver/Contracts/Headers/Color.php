<?php
declare(strict_types=1);

namespace PHPGui\Driver\Contracts\Headers;

use FFI\CData;

final class Color extends CData
{
    /**
     * Uint8 r;
     *
     * @var int
     */
    public int $r;

    /**
     * Uint8 g;
     *
     * @var int
     */
    public int $g;

    /**
     * Uint8 b;
     *
     * @var int
     */
    public int $b;

    /**
     * Uint8 a;
     *
     * @var int
     */
    public int $a;
}