<?php

namespace App\Windows;

use PHPGui\Ui\Position;
use PHPGui\Ui\Size;
use PHPGui\Ui\Widgets\Button;
use PHPGui\Ui\Window;

class SettingsWindow extends Window
{
    public Button $exitButton;

    public function __construct(string $title, Size $size, Position $position)
    {
        parent::__construct($title, $size, $position);
        $this->init();
    }

    public function init(): void
    {
        $this->exitButton = (new Button("Exit"))->setMarginAll(2)->setPaddingAll(2);
        $this->setWidget($this->exitButton);
    }
}