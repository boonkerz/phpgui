<?php

declare(strict_types=1);

namespace PHPGui\Driver\Contracts\Headers;

use PHPGui\Driver\Contracts\Headers\Version\Comparable;
use PHPGui\Driver\Contracts\Headers\Version\ComparableInterface;

/**
 * @psalm-import-type VersionStringType from VersionInterface
 */
final class Version implements ComparableInterface
{
    use Comparable;

    /**
     * @param VersionStringType $version
     */
    public function __construct(
        private readonly string $version,
    ) {
    }

    /**
     * @param VersionStringType $version
     * @return self
     */
    public static function fromString(string $version): self
    {
        return new self($version);
    }

    /**
     * @param VersionInterface $version
     * @return static
     */
    public static function fromVersion(VersionInterface $version): self
    {
        if ($version instanceof self) {
            return $version;
        }

        return self::fromString($version->toString());
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->version;
    }
}
