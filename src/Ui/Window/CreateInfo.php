<?php

declare(strict_types=1);

namespace PHPGui\Ui\Window;

use PHPGui\Ui\Position;
use PHPGui\Ui\Size;

class CreateInfo
{
    public const DEFAULT_WIDTH = 640;

    public const DEFAULT_HEIGHT = 480;

    public function __construct(
        public readonly string $title = '',
        public readonly Size $size = new Size(self::DEFAULT_WIDTH, self::DEFAULT_HEIGHT),
        public readonly ?Position $position = null,
        public readonly Mode $mode = Mode::NORMAL,
        public readonly bool $closable = true,
    ) {
    }
}
