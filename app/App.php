<?php
declare(strict_types=1);

namespace App;

use App\Controllers\MainController;
use PHPGui\Application\Application;
use PHPGui\Lifecycle\Lifecycle;

final class App extends Lifecycle
{
    public function __construct()
    {

        parent::__construct(new Application(__DIR__ ));
        $this->show(MainController::class);
    }
}