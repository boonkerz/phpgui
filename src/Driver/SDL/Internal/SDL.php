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
use PHPGui\Driver\SDL\Internal\SDL\GFX;
use Psr\SimpleCache\CacheInterface;

final class SDL extends Proxy
{

    use GFX;

    public readonly string $binary;
    private \PHPGui\Driver\Contracts\Headers\VersionInterface $version;
    private string $headers;

    public function __construct(
        ?string $binary = null,
        string|VersionInterface|null $version = null,
        #[ExpectedValues(flagsFromClass: Init::class)]
        ?int $init = null,
        ?CacheInterface $cache = null,
    ) {
        Runtime::assertAvailable();

        $this->binary = $binary ?? Library::getBinaryPathname();

        $version ??= Library::getVersion($this->binary);
        $this->version = \is_string($version)
            ? Version::create($version)
            : $version;

        $this->headers = Headers::cached($cache, $version);

        parent::__construct(\FFI::cdef($this->headers, $this->binary));


        if ($init !== null) {
            $this->init($init);
        }
    }

    public function init(
        #[ExpectedValues(flagsFromClass: Init::class)]
        int $flags,
    ): void {
        assert($this->version->gte('2.0.0'), new VersionException('SDL 2.0.0 required'));

        try {
            $code = $this->ffi->SDL_Init($flags);
        } catch (\Throwable $e) {
            throw new SDLException($e->getMessage(), (int)$e->getCode(), $e);
        }

        if ($code !== 0) {
            throw new SDLException($this->getError() ?? 'Unknown error', $code);
        }
    }
}