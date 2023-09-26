<?php

declare(strict_types=1);

namespace PHPGui\Driver\Contracts\Headers;

interface HeaderInterface extends \Stringable
{
    public function __toString(): string;
}
