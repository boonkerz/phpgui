<?php
declare(strict_types=1);

namespace PHPGui\Lifecycle;

use PHPGui\Application\Application;
use PHPGui\Controller\AbstractController;
use PHPGui\Event\Event;
use PHPGui\Event\EventType;
use PHPGui\Interface\EventLoop\LoopInterface;
use PHPGui\Interface\EventLoop\WorkerInterface;
use PHPGui\Interface\Renderer\RendererInterface;
use PHPGui\Interface\Ui\WindowInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Browser;

class Lifecycle implements WorkerInterface
{
    //public WindowInterface $window;

    //public RendererInterface $renderer;

    //public ViewportInterface $viewport;

    public LoopInterface $loop;

    public Application $app;

    protected ?object $controller = null;

    protected Collection $contextCollection;
    protected ?Context $context = null;

    public function __construct(Application $app)
    {
        $app->instance(self::class, $this);
        $app->instance(static::class, $this);
        $this->contextCollection = new Collection();
        $this->app = $app;
        $this->loop = $app->make(LoopInterface::class);

        $this->loop->use($this);
    }


    public function onUpdate(float $delta): void
    {
        $this->contextCollection->map(fn(Context $context) => $context->update($delta));
        if ($this->context !== null) {
            $this->context->update($delta);
        }
    }

    public function onRender(float $delta): void
    {
        $this->contextCollection->map(fn(Context $context) => $context->render($delta));
        if ($this->context !== null) {
            $this->context->render($delta);
        }
    }

    public function onEvent(Event $event): void
    {
        $this->defaultEventLogic($event);
        $this->contextCollection->map(fn(Context $context) => $context->event($event));
        if ($this->context !== null) {
            $this->context->event($event);
        }
    }

    protected function defaultEventLogic(Event $event): void
    {
        switch ($event->getType()) {
            case EventType::WINDOW_FOCUS_LOST:
                $this->loop->pause();
                break;
            case EventType::WINDOW_FOCUS_GAINED:
                $this->loop->resume();
                break;
            case EventType::QUIT:
                $this->contextCollection->map(fn(Context $context) => $context->unload());
                $this->contextCollection = new Collection();
                $this->loop->stop();
                break;
        }
    }

    public function onPause(): void
    {
        if ($this->context !== null) {
            $this->context->pause();
        }
    }

    public function onResume(): void
    {
        if ($this->context !== null) {
            $this->context->resume();
        }
    }

    public function show(string $controller, array $arguments = []): void
    {

        if ($this->contextCollection !== null) {
            $this->contextCollection->map(fn(Context $context) => $context->hide());
        }

        foreach ($arguments as $name => $argument) {
            if (\class_exists($name) || \interface_exists($name)) {
                $this->app->instance($name, $argument);
            }
        }

        $controller = $this->app->make($controller);
        $context = $this->app->make(Context::class, [
            'context' => $controller,
        ]);

        $context->show();

        $this->contextCollection->add($context);
    }

    public function close(AbstractController $self): void
    {
        $this->contextCollection->removeItem($self);
    }

    public function run(): void
    {
        $this->app->run();
    }
}