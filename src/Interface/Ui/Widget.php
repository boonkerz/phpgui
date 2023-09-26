<?php
declare(strict_types=1);

namespace PHPGui\Interface\Ui;

use PHPGui\Interface\Driver\Renderer\Element;

interface Widget
{
    public function getRenderElement(): ?Element;
    public function setRenderElement(Element $renderElement): void;
    public function getUuid(): string;

}