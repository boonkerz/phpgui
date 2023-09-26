<?php
declare(strict_types=1);

namespace PHPGui\Lifecycle;

use PHPGui\Application\Application;
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

    protected ?Context $context = null;

    public function __construct(Application $app)
    {
        $app->instance(self::class, $this);
        $app->instance(static::class, $this);

        $this->app = $app;

        //$this->window = $app->make(WindowInterface::class);
        //$this->renderer = $app->make(RendererInterface::class);
        /*$this->viewport = $app->make(ViewportInterface::class);*/
        $this->loop = $app->make(LoopInterface::class);

        $this->loop->use($this);
    }


    public function onUpdate(float $delta): void
    {
        if ($this->context !== null) {
            $this->context->update($delta);
        }
    }

    public function onRender(float $delta): void
    {
        //$this->renderer->clear();

        if ($this->context !== null) {
            $this->context->render($delta);
        }

        //$this->renderer->present();
    }

    public function onEvent(Event $event): void
    {
        $this->defaultEventLogic($event);

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
                $this->loop->stop();
                $this->context->unload();
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

        if ($this->context !== null) {
            $this->context->hide();
        }

        foreach ($arguments as $name => $argument) {
            if (\class_exists($name) || \interface_exists($name)) {
                $this->app->instance($name, $argument);
            }
        }

        $this->controller = $this->app->make($controller);
        $this->context = $this->app->make(Context::class, [
            'context' => $this->controller,
        ]);

        $this->context->show();
    }

    public function run(): void
    {
        //$this->window->show();

        $this->app->run();
    }
}