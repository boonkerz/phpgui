<?php
declare(strict_types=1);

namespace PHPGui\Ui\Widgets;

use PHPGui\Ui\Enum\State;
use PHPGui\Ui\Style\Color;
use PHPGui\Ui\Style\Font;
use PHPGui\Ui\Style\Style;

class Button extends Base implements \PHPGui\Interface\Ui\Widget
{

    public \Closure $click;

    public function __construct(public string $text = "Label")
    {
        $this->setState(State::NORMAL);
        $this->addStateStyle(State::NORMAL, (new Style())->setFont((new Font())->setSize(14)));
        $this->addStateStyle(State::HOVER, (new Style())->setBackgroundColor(new Color(128,128,128,255))->setFont((new Font())->setSize(14)));
        return $this;
    }

    public function setOnClick(\Closure $onClick): self
    {
        $this->click = $onClick;
        return $this;
    }

    public function onClick(): void
    {
        ($this->click)();
    }


}