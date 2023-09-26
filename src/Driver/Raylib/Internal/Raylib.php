<?php

declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal;

use FFI\Env\Runtime;
use FFI\Proxy\Proxy;
use PHPGui\Driver\Raylib\Internal\Headers\Version;
use PHPGui\Driver\SDL\Internal\Exception\VersionException;
use Psr\SimpleCache\CacheInterface;

final class Raylib extends Proxy
{

    public readonly string $binary;
    private \PHPGui\Driver\Contracts\Headers\VersionInterface $version;
    private string $headers;

    public function __construct(
        ?string $binary = null,
        string|VersionInterface|null $version = null,
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

        $this->init();
    }

    public function init(
    ): void {
        assert($this->version->gte('4.5.0'), new VersionException('raylib 4.5.0 required'));
    }
}