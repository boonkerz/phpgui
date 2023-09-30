<?php
declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Model\Server;
use App\Windows\MainWindow;
use App\Windows\SettingsWindow;
use parallel\Events;
use parallel\Runtime;
use PHPGui\Controller\AbstractController;
use PHPGui\Event\Event;
use PHPGui\Event\EventType;
use PHPGui\Interface\Driver\Driver;
use PHPGui\Lifecycle\Annotation\OnUpdate;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Position;
use PHPGui\Ui\Size;
use PHPGui\Ui\Style\Font;
use PHPGui\Ui\Widgets\Label;
use PHPGui\Ui\Window;

class SettingsController extends AbstractController
{

    protected SettingsWindow $window;

    public function __construct(App $app, Driver $driver)
    {
        parent::__construct($app, $driver);
        $this->init();
    }

    public function init(): void
    {
        $this->window = new SettingsWindow(
            title: 'Settings',
            size: new Size(200, 200),
            position: new Position(50, 50)
        );

        $this->window->exitButton->setOnClick(fn() => $this->clickExit());
    }

    private function clickExit(): void {
        $this->closeWindow();
    }
}