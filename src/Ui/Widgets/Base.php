<?php

namespace PHPGui\Ui\Widgets;

use PHPGui\Interface\Driver\Renderer\Element;
use PHPGui\Interface\Ui\Widget;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Style\Border;
use PHPGui\Ui\Style\Style;

class Base implements Widget
{

    private string $uuid;

    private State $state;

    private array $stateStyles = [];

    private $margin = [0, 0, 0, 0];

    private $padding = [1, 1, 1, 1];

    private Border $border;

    private ?Element $renderElement = null;

    public function getBorder(): Border
    {
        return $this->border?? new Border();
    }

    public function setBorder(Border $border): self
    {
        $this->border = $border;
        return $this;
    }

    public function getMargin(Pos $pos = Pos::LEFT): int
    {
        return $this->margin[$pos->value]?? 0;
    }

    public function getPadding(Pos $pos = Pos::LEFT): int
    {
        return $this->padding[$pos->value]?? 0;
    }

    public function setPaddingAll(int $value): self
    {
        $this->padding = [$value, $value, $value, $value];
        return $this;
    }

    public function setMarginAll(int $value): self
    {
        $this->margin = [$value, $value, $value, $value];
        return $this;
    }



    public function getState(): State
    {
        return $this->state;
    }

    public function setState(State $state): void
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        if(!$this->uuid) {
            $this->uuid = (string)rand();
        }
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getRenderElement(): ?Element
    {
        return $this->renderElement;
    }

    public function setRenderElement(Element $renderElement): void
    {
        $this->renderElement = $renderElement;
    }

    public function getStateStyles(): array
    {
        return $this->stateStyles;
    }

    public function setStateStyles(array $stateStyles): void
    {
        $this->stateStyles = $stateStyles;
    }

    public function addStateStyle(State $state = State::NORMAL, Style $style = new Style()): self
    {
        $this->stateStyles[$state->name] = $style;
        return $this;
    }

    public function getStateStyle(State $state = State::NORMAL): Style
    {

        return $this->stateStyles[$state->name]?? $this->stateStyles[State::NORMAL->name]?? new Style();
    }
}