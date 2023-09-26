<?php


declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal;

use PHPGui\Driver\Contracts\Headers\HeaderInterface;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\SDL\Internal\Headers\Platform;
use Psr\SimpleCache\CacheInterface;

final class Headers
{
    public static function cached(?CacheInterface $cache, VersionInterface $version, Type $type = Type::SDL): string
    {
        if ($cache === null) {
            return (string)self::get($version, $type);
        }

        $key = \sprintf('sdl2.%s.%s.h', \strtolower(\PHP_OS_FAMILY), $version->toString());

        if (!$cache->has($key)) {
            $cache->set($key, (string)self::get($version, $type));
        }

        return $cache->get($key);
    }

    public static function get(VersionInterface $version, Type $type = Type::SDL): HeaderInterface
    {

        return match ($type) {
            Type::SDL => \PHPGui\Driver\SDL\Internal\Headers\SDL::create(
                platform: self::platform(),
                version: $version,
            ),
            Type::SDL_TTF => \PHPGui\Driver\SDL\Internal\Headers\SDL_TTF::create(
                platform: self::platform(),
                version: $version,
            ),
            Type::SDL_IMAGE => \PHPGui\Driver\SDL\Internal\Headers\SDL_IMAGE::create(
                platform: self::platform(),
                version: $version,
            )
        };

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
