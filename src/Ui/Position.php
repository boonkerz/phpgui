<?php

declare(strict_types=1);

namespace PHPGui\Ui;

class Position
{
    public function __construct(
        public int $x = 0,
        public int $y = 0,
    ) {
    }
}
