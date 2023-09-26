<?php

declare(strict_types=1);

namespace PHPGui\Interface\Driver\Ui;

use PHPGui\Interface\Ui\WindowInterface;

interface ManagerInterface extends \Traversable, \Countable
{
    public function detach(WindowInterface $window): void;

    public function run(): void;
}
