<?php

declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal\Headers;


use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\SDL\Internal\Type;

final class Downloader
{
    /**
     * @param VersionInterface $version
     * @return string
     */
    private static function getArchiveUrl(VersionInterface $version, Type $type = Type::SDL): string
    {
        return match ($type) {
            Type::SDL => \vsprintf('https://github.com/libsdl-org/SDL/archive/refs/tags/release-%s.zip', [
                $version->toString(),
            ]),
            Type::SDL_TTF => \vsprintf('https://github.com/libsdl-org/SDL_ttf/archive/refs/tags/release-%s.zip', [
                $version->toString(),
            ]),
            Type::SDL_IMAGE =>\vsprintf('https://github.com/libsdl-org/SDL_image/archive/refs/tags/release-%s.zip', [
                $version->toString(),
            ])
        };
    }

    /**
     * @param VersionInterface $version
     * @return string
     */
    private static function getArchiveTemp(VersionInterface $version, Type $type = Type::SDL): string
    {
        return match($type) {
            Type::SDL => \sys_get_temp_dir() . '/sdl2-headers-' . $version->toString() . '.zip',
            Type::SDL_TTF => \sys_get_temp_dir() . '/sdl2_ttf-headers-' . $version->toString() . '.zip',
            Type::SDL_IMAGE => \sys_get_temp_dir() . '/sdl2_image-headers-' . $version->toString() . '.zip'
        };
    }

    /**
     * @param VersionInterface $version
     * @return string
     */
    private static function downloadArchive(VersionInterface $version, Type $type = Type::SDL): string
    {
        $urlFrom = self::getArchiveUrl($version, $type);
        $urlTo = self::getArchiveTemp($version, $type);

        if (!\is_file($urlTo) || !\filesize($urlTo)) {
            $from = @\fopen($urlFrom, 'rb');
            $to = @\fopen($urlTo, 'ab+');

            if ($error = \error_get_last()) {
                throw new \RuntimeException($error['message']);
            }

            \stream_copy_to_stream($from, $to);
        }

        return $urlTo;
    }

    /**
     * @param VersionInterface $version
     * @return iterable<\PharFileInfo>
     */
    private static function readArchive(VersionInterface $version, Type $type): iterable
    {
        return new \RecursiveIteratorIterator(new \PharData(
            self::downloadArchive($version, $type)
        ));
    }

    /**
     * @param VersionInterface $version
     * @param non-empty-string $directory
     * @return void
     */
    public static function download(VersionInterface $version, string $directory, Type $type = Type::SDL): void
    {
        $directory = $directory . '/' . $version->toString();

        if (!\is_dir($directory)) {
            \mkdir($directory, 0777, true);
        }

        foreach (self::readArchive($version, $type) as $file) {
            if ($file->isFile() && ((\str_contains($file->getPathname(), 'include') && \str_ends_with($file->getPathname(), '.h')) || $file->getFilename() == 'SDL_ttf.h' )) {
                $name = \pathinfo($file->getPathname(), \PATHINFO_BASENAME);

                \file_put_contents($directory . '/' . $name, $file->getContent());
            }
        }
    }
}
