<?php

declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal;

use FFI\Env\Runtime;
use FFI\Location\Locator;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\SDL\Internal\Headers\Version;

final class Library
{

    private static array $versions = [];

    public static function getVersion(string $binary = null, Type $type = Type::SDL): VersionInterface
    {
        $binary ??= self::getBinaryPathname();

        if ($type == Type::SDL && !isset(self::$versions[$binary])) {
            Runtime::assertAvailable();

            /** @var object|\FFI $ffi */
            $ffi = \FFI::cdef(<<<'CPP'
                typedef struct Version {
                    uint8_t major;
                    uint8_t minor;
                    uint8_t patch;
                } Version;

                void SDL_GetVersion(Version *ver);
            CPP, $binary);

            $version = $ffi->new('Version');
            $ffi->SDL_GetVersion(\FFI::addr($version));

            self::$versions[$binary] = \vsprintf('%d.%d.%d', [
                $version->major,
                $version->minor,
                $version->patch
            ]);
        }

        if ($type == Type::SDL_TTF && !isset(self::$versions[$binary])) {
            Runtime::assertAvailable();

            /** @var object|\FFI $ffi */
            $ffi = \FFI::cdef(<<<'CPP'
                typedef struct Version {
                    uint8_t major;
                    uint8_t minor;
                    uint8_t patch;
                } Version;

                extern const Version* TTF_Linked_Version(void);
            CPP, $binary);

            $version = $ffi->TTF_Linked_Version();

            self::$versions[$binary] = \vsprintf('%d.%d.%d', [
                $version->major,
                $version->minor,
                $version->patch
            ]);
        }

        return Version::create(self::$versions[$binary]);
    }

    public static function getBinaryPathname(Type $type = Type::SDL): string
    {
        $binary = null;

        $name = match($type) {
            Type::SDL => 'SDL2.dll',
            Type::SDL_TTF => 'SDL2_ttf.dll',
            Type::SDL_IMAGE => 'SDL2_image.dll'
        };

        return $binary ??= match (\PHP_OS_FAMILY) {
            'Windows' => Locator::resolve(
                __DIR__ . '/../../../../vendor/bin/'. $name,
                $name
            )
                ?? throw new \RuntimeException(<<<'error'
                Could not load [SDL2.dll].

                Please make sure the SDL2 library is installed or specify
                the path to the binary explicitly.
                error),
            'Linux', 'BSD' => Locator::resolve('libSDL2-2.0.so.0')
                ?? throw new \RuntimeException(<<<'error'
                Could not load [libSDL2-2.0.so.0].

                Please make sure the SDL2 library is installed or specify
                the path to the binary explicitly.
                error),
            'Darwin' => Locator::resolve('libSDL2-2.0.0.dylib')
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
