<?php

declare(strict_types=1);

namespace PHPGui\Interface\Ui;

use PHPGui\Ui\Position;
use PHPGui\Ui\Size;

interface WindowInterface
{
    public function getTitle(): string;

    public function getSize(): Size;

    public function getPosition(): Position;

    public function getHandle(): HandleInterface;

    /**
     * @return void
     */
    public function show(): void;

    /**
     * @return void
     */
    public function hide(): void;

    /**
     * @return void
     */
    public function close(): void;

    /**
     * @return bool
     */
    public function isClosed(): bool;
}
