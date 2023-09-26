<?php

declare(strict_types=1);

namespace PHPGui\Driver\Contracts\Headers;

interface VersionInterface
{
    public function toString(): string;
}
