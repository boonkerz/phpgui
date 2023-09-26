<?php

declare(strict_types=1);

namespace PHPGui\Lifecycle\Annotation;

#[\Attribute(\Attribute::TARGET_METHOD)]
class OnUnload extends LifecycleAttribute
{
}
