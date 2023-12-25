<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Main;
use App\Model\AppSetting;
use App\Model\Server;
use App\Windows\MainWindow;
use App\Windows\SettingsWindow;
use parallel\Events;
use parallel\Runtime;
use PHPGui\Application\Storage;
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
    /**
     * @var AppSetting|mixed|object
     */
    private mixed $setting;

    public function __construct(Main $app, Driver $driver, private Storage $storage)
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
        $this->setting = $this->storage->loadModel(AppSetting::class);

        $this->window->apiKey->setValue($this->setting->getHetznerApiKey());
        $this->window->saveButton->setOnClick(fn() => $this->clickExit());
    }

    private function clickExit(): void {
        $this->setting->setHetznerApiKey($this->window->apiKey->getValue());
        $this->storage->saveModel($this->setting);
        $this->closeWindow();
    }
}