<?php

namespace PHPGui\Driver\SDL\Renderer;

use PHPGui\Driver\SDL\Ui\Window;
use PHPGui\Ui\Enum\Pos;
use PHPGui\Ui\ViewPort;

trait Border
{
    public static function drawBorder(Window $window, ViewPort $availableViewPort, \PHPGui\Ui\Widgets\Base $actWidget): void {

        if($actWidget->getBorder()->widthLeft > 0) {
            $window->getSDL()->boxRGBA(
                $window->getRenderPtr(),
                $availableViewPort->x + $actWidget->getMargin(Pos::LEFT),
                $availableViewPort->y + $actWidget->getMargin(Pos::TOP),
                $availableViewPort->x + $actWidget->getMargin(Pos::LEFT) + $actWidget->getBorder()->widthTop - 1,
                $availableViewPort->y + $availableViewPort->height - $actWidget->getMargin(Pos::TOP) - $actWidget->getMargin(Pos::BOTTOM),
                $actWidget->getBorder()->colorLeft->r,
                $actWidget->getBorder()->colorLeft->g,
                $actWidget->getBorder()->colorLeft->b,
                $actWidget->getBorder()->colorLeft->a
            );
        }

        if($actWidget->getBorder()->widthTop > 0) {
            $window->getSDL()->boxRGBA(
                $window->getRenderPtr(),
                $availableViewPort->x + $actWidget->getMargin(Pos::LEFT),
                $availableViewPort->y + $actWidget->getMargin(Pos::TOP),
                $availableViewPort->x + $actWidget->getMargin(Pos::LEFT) + $availableViewPort->width,
                $availableViewPort->y + $actWidget->getMargin(Pos::TOP) + $actWidget->getBorder()->widthTop - 1,
                $actWidget->getBorder()->colorLeft->r,
                $actWidget->getBorder()->colorLeft->g,
                $actWidget->getBorder()->colorLeft->b,
                $actWidget->getBorder()->colorLeft->a
            );
        }

        if($actWidget->getBorder()->widthRight > 0) {
            $window->getSDL()->boxRGBA(
                $window->getRenderPtr(),
                $availableViewPort->x - $actWidget->getMargin(Pos::LEFT) - $actWidget->getMargin(Pos::RIGHT) + $availableViewPort->width - $actWidget->getBorder()->widthRight + 1,
                $availableViewPort->y + $actWidget->getMargin(Pos::TOP),
                $availableViewPort->x + $actWidget->getMargin(Pos::LEFT) - $actWidget->getMargin(Pos::RIGHT) + $availableViewPort->width,
                $availableViewPort->y - $actWidget->getMargin(Pos::TOP) + $availableViewPort->height - $actWidget->getMargin(Pos::BOTTOM),
                $actWidget->getBorder()->colorLeft->r,
                $actWidget->getBorder()->colorLeft->g,
                $actWidget->getBorder()->colorLeft->b,
                $actWidget->getBorder()->colorLeft->a
            );
        }

        if($actWidget->getBorder()->widthBottom > 0) {
            $window->getSDL()->boxRGBA(
                $window->getRenderPtr(),
                $availableViewPort->x + $actWidget->getMargin(Pos::LEFT),
                $availableViewPort->y + $availableViewPort->height - $actWidget->getMargin(Pos::TOP) - $actWidget->getMargin(Pos::BOTTOM) - $actWidget->getBorder()->widthBottom + 1,
                $availableViewPort->x - $actWidget->getMargin(Pos::LEFT) + $availableViewPort->width - $actWidget->getMargin(Pos::RIGHT),
                $availableViewPort->y + $availableViewPort->height - $actWidget->getMargin(Pos::TOP) - $actWidget->getMargin(Pos::BOTTOM),
                $actWidget->getBorder()->colorLeft->r,
                $actWidget->getBorder()->colorLeft->g,
                $actWidget->getBorder()->colorLeft->b,
                $actWidget->getBorder()->colorLeft->a
            );
        }
    }
}