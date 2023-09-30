<?php
declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Windows\SimpleWindow;
use PHPGui\Controller\AbstractController;
use PHPGui\Event\Event;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Driver;
use PHPGui\Ui\Position;
use PHPGui\Ui\Size;

class SimpleController extends AbstractController
{
    protected SimpleWindow $window;

    public function __construct(App $app, Driver $driver)
    {
        parent::__construct($app, $driver);
        $this->init();
    }

    public function init(): void
    {
        $this->window = new SimpleWindow(
            title: 'SIMPLE Demo App',
            size: new Size(800, 600),
            position: new Position(50,50)
        );
        $this->window->exitButton->setOnClick(fn() => $this->clickExit());
        $this->window->settingsButton->setOnClick(fn() => $this->clickOpenSettings());
    }

    private function clickExit(): void {
        $this->app->onEvent(new Event(type: EventType::QUIT));
    }

    private function clickOpenSettings()
    {
        $this->app->show(SettingsController::class);
    }
}