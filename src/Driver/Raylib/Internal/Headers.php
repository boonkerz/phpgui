<?php


declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal;

use PHPGui\Driver\Contracts\Headers\HeaderInterface;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\Raylib\Internal\Headers\Platform;
use Psr\SimpleCache\CacheInterface;

final class Headers
{
    public static function cached(?CacheInterface $cache, VersionInterface $version): string
    {
        if ($cache === null) {
            return (string)self::get($version);
        }

        $key = \sprintf('raylib.%s.%s.h', \strtolower(\PHP_OS_FAMILY), $version->toString());

        if (!$cache->has($key)) {
            $cache->set($key, (string)self::get($version));
        }

        return $cache->get($key);
    }

    public static function get(VersionInterface $version): HeaderInterface
    {

        return \PHPGui\Driver\Raylib\Internal\Headers\Raylib::create(
            platform: self::platform(),
            version: $version,
        );

    }

    public static function platform(): Platform
    {
        return  match (\PHP_OS_FAMILY) {
            'Windows' => Platform::WINDOWS,
            'Linux' => Platform::LINUX,
            'BSD' => Platform::FREEBSD,
            'Darwin' => Platform::DARWIN,
            default => null,
        };
    }
}
