<?php

declare(strict_types=1);

namespace PHPGui\Lifecycle;

use PHPGui\Application\Application;
use PHPGui\Event\Event;
use PHPGui\Event\KeyEvent;
use PHPGui\Event\MoveEvent;
use PHPGui\Event\MoveUpEvent;
use PHPGui\Event\TextInputEvent;
use PHPGui\Lifecycle\Annotation;
use PHPGui\Lifecycle\Annotation\LifecycleAttribute;
use PHPGui\Lifecycle\Annotation\OnEvent;
use PHPGui\Lifecycle\Annotation\OnLoad;
use PHPGui\Lifecycle\Annotation\OnMouseMove;
use PHPGui\Lifecycle\Annotation\OnMouseUp;
use PHPGui\Lifecycle\Annotation\OnRender;
use PHPGui\Lifecycle\Annotation\OnShow;
use PHPGui\Lifecycle\Annotation\OnUnload;
use PHPGui\Lifecycle\Annotation\OnUpdate;

class Context
{
    public const TYPE_UPDATE = 0x00;

    public const TYPE_RENDER = 0x01;

    public const TYPE_SHOW = 0x02;

    public const TYPE_HIDE = 0x03;

    public const TYPE_PAUSE = 0x04;

    public const TYPE_RESUME = 0x05;

    public const TYPE_LOAD = 0x06;

    public const TYPE_UNLOAD = 0x07;

    private array $callbacks = [
        self::TYPE_UPDATE => [],
        self::TYPE_RENDER => [],
        self::TYPE_SHOW   => [],
        self::TYPE_HIDE   => [],
        self::TYPE_PAUSE  => [],
        self::TYPE_RESUME => [],
        self::TYPE_LOAD   => [],
        self::TYPE_UNLOAD => [],
    ];

    private array $mappings = [
        OnUpdate::class => self::TYPE_UPDATE,
        OnRender::class => self::TYPE_RENDER,
        OnShow::class   => self::TYPE_SHOW,
        //OnHide::class   => self::TYPE_HIDE,
        //OnPause::class  => self::TYPE_PAUSE,
        //OnResume::class => self::TYPE_RESUME,
        OnLoad::class   => self::TYPE_LOAD,
        OnUnload::class => self::TYPE_UNLOAD,
    ];

    private array $events = [];

    private Application $app;

    public function __construct(Application $app, object $context)
    {
        $this->app = $app;

        /**
         * @var \ReflectionMethod $ref
         * @var Annotation $annotation
         */
        foreach ($this->attributes($context) as $ref => $annotation) {
            $method = $ref->getClosure($context);

            if ($annotation instanceof OnEvent) {
                $this->events[] = [$annotation, $method];

                continue;
            }

            $this->callbacks[$this->mappings[\get_class($annotation)]][] = $method;
        }

        $this->load();
    }

    private function attributes(object $context): iterable
    {
        $class = new \ReflectionObject($context);

        foreach ($class->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
            $attributes = $method->getAttributes(LifecycleAttribute::class, \ReflectionAttribute::IS_INSTANCEOF);

            foreach ($attributes as $attribute) {
                yield $method => $attribute->newInstance();
            }
        }
    }

    private function load(): void
    {
        while (\count($this->callbacks[self::TYPE_LOAD]) > 0) {
            $handler = \array_pop($this->callbacks[self::TYPE_LOAD]);

            $this->app->call($handler);
        }
    }

    public function unload(): void
    {
        while (\count($this->callbacks[self::TYPE_UNLOAD]) > 0) {
            $handler = \array_pop($this->callbacks[self::TYPE_UNLOAD]);

            $this->app->call($handler);
        }
    }

    public function update(float $delta): void
    {
        foreach ($this->callbacks[self::TYPE_UPDATE] as $callback) {
            $callback($delta);
        }
    }

    public function render(float $delta): void
    {
        foreach ($this->callbacks[self::TYPE_RENDER] as $callback) {
            $callback($delta);
        }
    }

    public function event(Event $event): void
    {
        /**
         * @var OnEvent $attribute
         * @var \Closure $callback
         */
        foreach ($this->events as [$attribute, $callback]) {
            $handle = $this->handle($attribute, $event);

            if ($handle !== null) {
                $this->app->call($callback, ['event' => $handle]);
            }
        }
    }

    private function handle(LifecycleAttribute $attr, Event $event): ?Event
    {
        if($attr::class == OnMouseMove::class && $event::class == MoveEvent::class) {
            return $event;
        }
        if($attr::class == OnMouseUp::class && $event::class == MoveUpEvent::class) {
            return $event;
        }
        if($attr::class == Annotation\OnTextInput::class && $event::class == TextInputEvent::class) {
            return $event;
        }

        if($attr::class == Annotation\OnKey::class && $event::class == KeyEvent::class) {
            return $event;
        }

        return null;
    }

    public function show(): void
    {
        foreach ($this->callbacks[self::TYPE_SHOW] as $callback) {
            $this->app->call($callback);
        }
    }

    public function hide(): void
    {
        foreach ($this->callbacks[self::TYPE_HIDE] as $callback) {
            $this->app->call($callback);
        }
    }

    public function pause(): void
    {
        foreach ($this->callbacks[self::TYPE_PAUSE] as $callback) {
            $this->app->call($callback);
        }
    }

    public function resume(): void
    {
        foreach ($this->callbacks[self::TYPE_PAUSE] as $callback) {
            $this->app->call($callback);
        }
    }
}
