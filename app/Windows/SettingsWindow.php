<?php

namespace App\Windows;

use PHPGui\Ui\Enum\AlignType;
use PHPGui\Ui\Enum\StackpanelMode;
use PHPGui\Ui\Position;
use PHPGui\Ui\Size;
use PHPGui\Ui\Widgets\Button;
use PHPGui\Ui\Widgets\Label;
use PHPGui\Ui\Widgets\StackPanel;
use PHPGui\Ui\Widgets\TextEdit;
use PHPGui\Ui\Window;

class SettingsWindow extends Window
{
    public Button $saveButton;
    public TextEdit $apiKey;

    public function __construct(string $title, Size $size, Position $position)
    {
        parent::__construct($title, $size, $position);
        $this->init();
    }

    public function init(): void
    {
        $this->saveButton = (new Button("Save"))->setMarginAll(2)->setPaddingAll(2);
        $this->apiKey = new TextEdit("");

        $this->setWidget((new StackPanel(widgets: [
            (new StackPanel(widgets: [(new Label("Hetzner Api Key"))->setWidth(150), $this->apiKey]))->setAlign(AlignType::HORIZONTAL)->setMode(StackpanelMode::STACK),
            $this->saveButton
        ]))->setColumnSizes([20,20,20])->setAlign(AlignType::VERTICAL));
    }
}