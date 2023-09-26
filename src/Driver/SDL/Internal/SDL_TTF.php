<?php

declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal;

use FFI\Env\Runtime;
use FFI\Proxy\Proxy;
use JetBrains\PhpStorm\ExpectedValues;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\SDL\Internal\Exception\SDLException;
use PHPGui\Driver\SDL\Internal\Exception\VersionException;
use PHPGui\Driver\SDL\Internal\Headers\Version;
use PHPGui\Driver\SDL\Internal\TTF\Marshaller;
use PHPGui\Driver\SDL\Internal\TTF\Style;

final class SDL_TTF extends Proxy implements Style
{
    use Marshaller;

    public readonly string $binary;
    private \PHPGui\Driver\Contracts\Headers\VersionInterface $version;
    private string $headers;

    protected SDL $sdl;
    public function __construct(
        SDL $sdl,
        ?string $binary = null,
        string|VersionInterface|null $version = null,
        #[ExpectedValues(flagsFromClass: Init::class)]
        ?CacheInterface $cache = null,
    ) {
        Runtime::assertAvailable();

        $this->sdl = $sdl;
        $this->binary = $binary ?? Library::getBinaryPathname(Type::SDL_TTF);

        $version ??= Library::getVersion($this->binary, Type::SDL_TTF);
        $this->version = \is_string($version)
            ? Version::create($version)
            : $version;

        $this->headers = Headers::cached($cache, $version, Type::SDL_TTF);
        file_put_contents(__DIR__ .'/../../../../resources/ttf.h', $this->headers);
        parent::__construct(\FFI::cdef($this->headers, $this->binary));

        $this->init();
    }

    public function init(): void
    {
        assert($this->version->gte('2.0.0'), new VersionException('SDL_ttf 2.0.0 required'));

        try {
            $code = $this->ffi->TTF_Init();
        } catch (\Throwable $e) {
            throw new SDLException($e->getMessage(), (int)$e->getCode(), $e);
        }

        if ($code !== 0) {
            throw new SDLException($this->getError() ?? 'Unknown error', $code);
        }
    }
}