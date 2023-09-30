<?php

namespace PHPGui\Ui\Widgets\Menu;

use PHPGui\Shared\TypedCollection;

class Collection extends TypedCollection
{

    protected function type(): string
    {
        return Item::class;
    }
}