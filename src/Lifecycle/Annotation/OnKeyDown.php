<?php

declare(strict_types=1);

namespace PHPGui\Lifecycle\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnKeyDown extends OnEvent
{
    public function __construct(
        public readonly KeyInterface $key,
    ) {
        parent::__construct(Type::SDL_KEYDOWN);
    }
}
