<?php

declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal;

use FFI\Env\Runtime;
use FFI\Location\Locator;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\Raylib\Internal\Headers\Version;

final class Library
{

    private static array $versions = [];

    public static function getVersion(string $binary = null): VersionInterface
    {
        $binary ??= self::getBinaryPathname();

        if (!isset(self::$versions[$binary])) {
            Runtime::assertAvailable();

            /** @var object|\FFI $ffi */
            $ffi = \FFI::cdef(<<<'CPP'
                char *raylib_version = RAYLIB_VERSION;
            CPP, $binary);

            $arr = $ffi->raylib_version;

            $arr = explode('.', \FFI::string($arr));

            self::$versions[$binary] = \vsprintf('%d.%d.%d', [
                $arr[0]?? 0,
                $arr[1]?? 0,
                $arr[2]?? 0
            ]);
        }

        return Version::create(self::$versions[$binary]);
    }

    public static function getBinaryPathname(): string
    {
        $binary = null;

        $name = 'raylib.dll';

        return $binary ??= match (\PHP_OS_FAMILY) {
            'Windows' => Locator::resolve(
                __DIR__ . '/../../../../vendor/bin/'. $name,
                $name
            )
                ?? throw new \RuntimeException(<<<'error'
                Could not load [raylib.dll].

                Please make sure the raylib library is installed or specify
                the path to the binary explicitly.
                error),
            'Linux', 'BSD' => Locator::resolve('raylib-2.0.so.0')
                ?? throw new \RuntimeException(<<<'error'
                Could not load [libSDL2-2.0.so.0].

                Please make sure the SDL2 library is installed or specify
                the path to the binary explicitly.
                error),
            'Darwin' => Locator::resolve('raylib-2.0.0.dylib')
                ?? throw new \RuntimeException(<<<'error'
                Could not load [libSDL2-2.0.0.dylib].

                Please make sure the SDL2 library is installed or specify
                the path to the binary explicitly.
                error),
            default => throw new \RuntimeException(
                'Could not detect library for ' . \PHP_OS
            )
        };
    }
}
