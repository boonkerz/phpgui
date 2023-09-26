<?php

namespace PHPGui\Interface\Driver;

use PHPGui\Event\Event;

interface Driver
{
    public function free(): void;
    public function initEventSystem(): void;

    public function pollEvent(): Event;
    public function quit(): void;
}