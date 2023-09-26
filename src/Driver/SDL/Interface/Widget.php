<?php

namespace PHPGui\Driver\SDL\Interface;

use PHPGui\Driver\SDL\Renderer\Clip;
use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Ui\Size;
use PHPGui\Ui\ViewPort;

interface Widget
{
    public function setClip(?Clip $clip): void;

    public function renderUi(ViewPort $availableViewPort, \PHPGui\Interface\Ui\Widget $actWidget): Size;
}