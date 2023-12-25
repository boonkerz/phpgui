<?php
declare(strict_types=1);

namespace App;

use App\Controllers\MainController;
use App\Controllers\SimpleController;
use PHPGui\Application\Application;
use PHPGui\Lifecycle\Lifecycle;

final class Main extends Lifecycle
{
    public function __construct()
    {
        parent::__construct(new Application(__DIR__ ));
        $this->show(MainController::class);
    }
}