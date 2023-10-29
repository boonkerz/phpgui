<?php

namespace App\Windows;

use PHPGui\Ui\Enum\AlignType;
use PHPGui\Ui\Enum\SizeType;
use PHPGui\Ui\Enum\StackpanelMode;
use PHPGui\Ui\Position;
use PHPGui\Ui\Size;
use PHPGui\Ui\Style\Border;
use PHPGui\Ui\Style\Color;
use PHPGui\Ui\Widgets\Button;
use PHPGui\Ui\Widgets\FPS;
use PHPGui\Ui\Widgets\Label;
use PHPGui\Ui\Widgets\StackPanel;
use PHPGui\Ui\Widgets\TabPanel;
use PHPGui\Ui\Widgets\TextEdit;
use PHPGui\Ui\Window;

class SimpleWindow extends Window
{
    public Button $exitButton;

    public Button $settingsButton;

    public function __construct(string $title, Size $size, Position $position)
    {
        parent::__construct($title, $size, $position);
        $this->init();
    }

    public function init(): void
    {
        $this->exitButton = (new Button("Exit"))->setMarginAll(2)->setPaddingAll(2);
        $this->settingsButton = (new Button("Settings"))->setMarginAll(2)->setPaddingAll(2);
        $this->setWidget(
            (new StackPanel(widgets:
                [
                    $this->exitButton, $this->settingsButton
                ]
            ))->setColumnSizes([30, 30])->setAlign(AlignType::VERTICAL)->setSizeType(SizeType::PX)
        );
    }
}