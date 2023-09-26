<?php
declare(strict_types=1);

namespace PHPGui\Ui\Widgets;

use PHPGui\Interface\Ui\Widget;
use PHPGui\Ui\Enum\AlignType;
use PHPGui\Ui\Enum\SizeType;
use PHPGui\Ui\Enum\StackpanelMode;

class StackPanel extends Base implements \PHPGui\Interface\Ui\Widget
{
    private array $columnSizes = [];
    private Collection $widgets;
    private SizeType $sizeType = SizeType::PERCENT;
    private AlignType $align = AlignType::VERTICAL;

    private StackpanelMode $mode = StackpanelMode::NORMAL;

    public function __construct(array $widgets = [])
    {
        $this->widgets = new Collection();
        foreach($widgets as $widget) {
            $this->widgets->add($widget);
        }
    }

    public function addWidget(Widget $widget): self
    {
        $this->widgets->add($widget);
        return $this;
    }

    public function setColumnSizes(array $sizes): self
    {
        $this->columnSizes = $sizes;

        return $this;
    }

    public function getColumnSize(int $col, int $availableSize): int
    {
        if(!isset($this->columnSizes[$col])) {
            return 0;
        }

        if($this->sizeType === SizeType::PERCENT) {
            return intval($availableSize / 100 * $this->columnSizes[$col]);
        }

        if($this->columnSizes[$col] === 0) {
            $tempHeight = 0;
            foreach($this->columnSizes as $size) {
                $tempHeight = $tempHeight + $size;
            }
            return $availableSize - $tempHeight;
        }

        return $this->columnSizes[$col];
    }

    public function setAlign(AlignType $align): self
    {
        $this->align = $align;
        return $this;
    }

    public function getAlign(): AlignType
    {
        return $this->align;
    }

    public function setSizeType(SizeType $sizeType): self
    {
        $this->sizeType = $sizeType;
        return $this;
    }

    public function getWidgets(): Collection
    {
        return $this->widgets;
    }

    public function getMode(): StackpanelMode
    {
        return $this->mode;
    }

    public function setMode(StackpanelMode $mode): self
    {
        $this->mode = $mode;
        return $this;
    }

    public function clearWidgets(): void
    {
        $this->widgets = new Collection();
    }
}