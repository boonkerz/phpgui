<?php

declare(strict_types=1);

namespace PHPGui\Interface\Driver\Ui;

use PHPGui\Interface\Ui\WindowInterface;
use PHPGui\Ui\Window\CreateInfo;

interface FactoryInterface
{
    public function create(CreateInfo $info = new CreateInfo()): WindowInterface;
}
