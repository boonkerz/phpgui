<?php

namespace App\Windows;

use App\Menu\MainMenu;
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

class MainWindow extends Window
{
    public Button $exitButton;
    public TextEdit $textEdit1;
    public TextEdit $textEdit2;

    public StackPanel $stackPanel;
    public Button $reloadButton;
    public Button $clearButton;
    public Button $settingsButton;

    public function __construct(string $title, Size $size, Position $position)
    {
        parent::__construct($title, $size, $position);
        $this->init();
    }

    public function init(): void
    {
        $this->exitButton = (new Button("Exit"))->setMarginAll(2)->setPaddingAll(2);
        $this->reloadButton = (new Button("Reload"))->setMarginAll(2)->setPaddingAll(2);
        $this->clearButton = (new Button("Clear"))->setMarginAll(2)->setPaddingAll(2);
        $this->settingsButton = (new Button("Settings"))->setMarginAll(2)->setPaddingAll(2);
        $this->textEdit1 = (new TextEdit("Firstname"))->setPaddingAll(4)->setMarginAll(5)->setBorder((new Border())->setAll(2, (new Color(0,0,0,255))));
        $this->textEdit2 = (new TextEdit("Lastname"))->setPaddingAll(4)->setMarginAll(5)->setBorder((new Border())->setAll(2, (new Color(0,0,0,255))));

        $this->stackPanel = (new StackPanel())->setAlign(AlignType::VERTICAL)->setMode(StackpanelMode::STACK);
        $this->setMenuBar(new MainMenu());

        $this->setWidget(
            (new StackPanel(widgets: [
                //(new StackPanel(widgets: [$this->exitButton, $this->settingsButton, $this->reloadButton, $this->clearButton]))->setAlign(AlignType::HORIZONTAL)->setMode(StackpanelMode::STACK),
                (new StackPanel(widgets:
                    [$this->stackPanel,
                        (new TabPanel(tabs: [
                            (new TabPanel\Tab(title: 'First Tab',widget: (new StackPanel(widgets: [$this->textEdit1, $this->textEdit2]))->setAlign(AlignType::HORIZONTAL)->setMode(StackpanelMode::STACK), active: true)),
                            (new TabPanel\Tab(title: 'Second Tab',widget: (new StackPanel(widgets: [$this->exitButton, $this->reloadButton, $this->clearButton]))->setAlign(AlignType::HORIZONTAL)->setMode(StackpanelMode::STACK)))
                            ]))
                    ]))
                    ->setColumnSizes([20, 80])->setAlign(AlignType::HORIZONTAL),
                 (new FPS())->setPaddingAll(3)
            ]))->setColumnSizes([0, 30])->setAlign(AlignType::VERTICAL)->setSizeType(SizeType::PX)
        );
    }
}