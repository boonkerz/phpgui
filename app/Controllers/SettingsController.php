<?php
declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Model\AppSetting;
use App\Model\Server;
use App\Windows\MainWindow;
use App\Windows\SettingsWindow;
use parallel\Events;
use parallel\Runtime;
euse PHPGui\Application\Storage;
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

    public function __construct(App $app, Driver $driver, private Storage $storage)
    {
        parent::__construct($app, $driver);
        $this->init();
    }

    public function init(): void
    {
        $this->window = new SettingsWindow(
            title: 'Settings',
            size: new Size(400, 200),
            position: new Position(50, 50)
        );

        $setting = $this->storage->loadModel(AppSetting::class);
        $this->window->apiKey = $setting->getHetznerApiKey();
        $this->window->saveButton->setOnClick(fn() => $this->clickExit());
    }

    private function clickExit(): void {
        $this->setting->setHetznerApiKey($this->window->apiKey->getValue());
        $this->setting->save();
        $this->closeWindow();
    }
}