<?php

namespace PHPGui\Lifecycle;

use PHPGui\Shared\TypedCollection;

class Collection extends TypedCollection
{

    protected function type(): string
    {
        return Context::class;
    }
}