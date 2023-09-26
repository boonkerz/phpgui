<?php

namespace PHPGui\Controller;

use App\App;
use parallel\Events;
use parallel\Runtime;
use PHPGui\Interface\Driver\Driver;
use PHPGui\Lifecycle\Annotation\OnShow;
use PHPGui\Lifecycle\Annotation\OnUnload;
use PHPGui\Lifecycle\Annotation\OnUpdate;
use PHPGui\Ui\Window;

class AbstractController
{

    public function __construct(protected App $app, private Driver $driver)
    {
    }

    #[OnShow]
    public function OnShow(): void
    {
        if($this->window !== null) {
            $this->window->setHandleId($this->driver->show($this->window));
        }
    }

    #[OnUnload]
    public function UnLoad(): void
    {
        if($this->window !== null) {
            $this->driver->quit();
        }
    }

    #[OnUpdate]
    public function OnUpdate(): void
    {
        if($this->window !== null) {
            $this->driver->update($this->window);
        }
    }

}