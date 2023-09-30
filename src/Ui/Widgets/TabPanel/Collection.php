<?php

namespace PHPGui\Ui\Widgets\TabPanel;

use PHPGui\Shared\TypedCollection;

final class Collection extends TypedCollection
{
    protected function type(): string
    {
        return Tab::class;
    }
}