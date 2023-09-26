<?php

declare(strict_types=1);

namespace PHPGui\Ui;

class Size
{
    public function __construct(
        public int $width = 0,
        public int $height = 0,
    ) {
    }
}
