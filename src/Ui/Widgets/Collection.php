<?php

namespace PHPGui\Ui\Widgets;

use PHPGui\Shared\TypedCollection;

final class Collection extends TypedCollection
{
    protected function type(): string
    {
        return Base::class;
    }
}