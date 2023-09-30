<?php

namespace PHPGui\Ui\Widgets\Menu;

use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Style\Color;
use PHPGui\Ui\Style\Font;
use PHPGui\Ui\Style\Style;

class MenuItem extends Item
{
    public ?\Closure $click = null;

    public ?Collection $subMenu = null;

    private int $height = 20;

    public function __construct(private string $title = "", ?array $subMenu = null, ?\Closure $onClick = null)
    {
        if($subMenu) {
            $this->subMenu = new Collection();
            foreach ($subMenu as $sub) {
                $this->subMenu->add($sub);
            }
        }
        $this->setState(State::NORMAL);
        $this->addStateStyle(State::NORMAL, (new Style())->setBackgroundColor(new Color(128,128,128,0))->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::HOVER, (new Style())->setBackgroundColor(new Color(128,128,128,255))->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::FOCUS, (new Style())->setBackgroundColor(new Color(79, 158, 227,255))->setFont((new Font())->setSize(14)));

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setOnClick(\Closure $onClick): self
    {
        $this->click = $onClick;
        return $this;
    }

    public function onClick(): void
    {
        if($this->click) {
            ($this->click)();
        }
    }

    public function setSubMenu(?Collection $subMenu): MenuItem
    {
        $this->subMenu = $subMenu;
        return $this;
    }

    public function getSubMenu(): ?Collection
    {
        return $this->subMenu;
    }

    public function setLevel(int $level): Item
    {
        if($this->subMenu) {
            foreach ($this->subMenu as $sub) {
                $sub->setLevel($level+1);
            }
        }
        return parent::setLevel($level); // TODO: Change the autogenerated stub
    }
}