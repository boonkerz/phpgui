<?php
declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Model\Server;
use App\Windows\MainWindow;
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

class MainController extends AbstractController
{
    private string $text = "binding";

    protected MainWindow $window;

    public Events $events;

    public Runtime $runtime;


    public function __construct(App $app, Driver $driver)
    {
        parent::__construct($app, $driver);
        $this->events = new Events();
        $this->events->setBlocking(false);
        $this->runtime = new Runtime(__DIR__ . '/../../vendor/autoload.php');
        $this->init();
    }

    public function init(): void
    {
        $this->window = new MainWindow(
            title: 'PHPGui Demo App',
            size: new Size(800, 600),
            position: new Position(50,50)
        );
        $this->window->exitButton->setOnClick(fn() => $this->clickExit());
        $this->window->reloadButton->setOnClick(fn() => $this->clickReload());
        $this->window->clearButton->setOnClick(fn() => $this->clickClear());
        $this->window->textEdit1->value = &$this->text;
        $this->window->textEdit2->value = &$this->text;
    }

    private function clickReload(): void {
        $this->events->addFuture('reloadServer', $this->runtime->run(function() {
            for ($i = 0;$i<20;$i++) {
                $servernames[] = new Server("Server: ". $i);
            }

            return serialize($servernames);
        }));
    }

    private function clickClear(): void {
        $this->window->stackPanel->clearWidgets();
    }

    private function clickExit(): void {
        $this->app->onEvent(new Event(type: EventType::QUIT));
    }

    #[OnUpdate]
    public function OnUpdate(): void {
        parent::OnUpdate();
        if($data = $this->events->poll()) {
            if($data->source == 'reloadServer') {
                $data = unserialize($data->value);
                /** @var Server $row */
                foreach ($data as $row) {
                    $this->window->stackPanel->addWidget((new Label($row->name))->setHeight(15)->setMarginAll(2));
                }
            }
        }
    }
}