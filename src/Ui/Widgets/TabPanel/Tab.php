<?php
declare(strict_types=1);

namespace PHPGui\Ui\Widgets\TabPanel;

use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Style\Color;
use PHPGui\Ui\Style\Font;
use PHPGui\Ui\Style\Style;
use PHPGui\Ui\Widgets\Base;

class Tab extends Base
{

    public function __construct(private bool $active = false, private string $title = "", private bool $closable = true, private ?Base $widget = null)
    {
        $this->setState(State::NORMAL);
        $this->addStateStyle(State::NORMAL, (new Style())->setBackgroundColor(new Color(211, 211, 211,255))->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::HOVER, (new Style())->setBackgroundColor(new Color(232,232,232,255))->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::ACTIVE, (new Style())->setBackgroundColor(new Color(232,232,232,255))->setFont((new Font())->setSize(14)));
        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Tab
    {
        $this->title = $title;
        return $this;
    }

    public function isClosable(): bool
    {
        return $this->closable;
    }

    public function setClosable(bool $closable): Tab
    {
        $this->closable = $closable;
        return $this;
    }

    public function getWidget(): ?Base
    {
        return $this->widget;
    }

    public function setWidget(Base $widget): self
    {
        $this->widget = $widget;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;
        return $this;
    }
}