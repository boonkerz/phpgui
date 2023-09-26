<?php

namespace PHPGui\Interface\Renderer;

interface RendererInterface
{
    /**
     * @return void
     */
    public function clear(): void;

    /**
     * @return void
     */
    public function present(): void;
}