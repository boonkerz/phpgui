<?php
declare(strict_types=1);

namespace PHPGui\Ui\Widgets;

use PHPGui\Ui\Widgets\TabPanel\Tab;

class TabPanel extends Base implements \PHPGui\Interface\Ui\Widget
{
    private \PHPGui\Ui\Widgets\TabPanel\Collection $tabs;

    public function __construct(array $tabs = [])
    {
        $this->tabs = new \PHPGui\Ui\Widgets\TabPanel\Collection();
        foreach($tabs as $tab) {
            $this->tabs->add($tab);
        }
        return $this;
    }

    public function addTab(Tab $tab): self
    {
        $this->tabs->add($tab);
        return $this;
    }

    public function getTabs(): TabPanel\Collection
    {
        return $this->tabs;
    }

    public function getActiveTab(): Tab
    {
        return $this->tabs->filter(function (Tab $tab) {
           if($tab->isActive()) return $tab;
        })->first();
    }
    public function setActiveTab(Tab $tabObj): void
    {
        $this->tabs->map(function(Tab $tab) use ($tabObj) {
            if($tabObj === $tab) {
                $tab->setActive(true);
            }else{
                $tab->setActive(false);
            }
        });

    }
}