<?php

declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal\Headers;


use PHPGui\Driver\Contracts\Headers\Version as CustomVersion;
use PHPGui\Driver\Contracts\Headers\Version\Comparable;
use PHPGui\Driver\Contracts\Headers\Version\ComparableInterface;
use PHPGui\Driver\Contracts\Headers\VersionInterface;

enum Version: string implements ComparableInterface
{
    use Comparable;


    case V4_5_0 = '4.5.0';

    public const LATEST = self::V4_5_0;

    /**
     * @param non-empty-string $version
     * @return VersionInterface
     */
    public static function create(string $version): VersionInterface
    {
        /** @var array<non-empty-string, VersionInterface> $versions */
        static $versions = [];

        return self::tryFrom($version)
            ?? $versions[$version]
                ??= CustomVersion::fromString($version);
    }

    /**
     * {@inheritDoc}
     */
    public function toString(): string
    {
        return $this->value;
    }
}
