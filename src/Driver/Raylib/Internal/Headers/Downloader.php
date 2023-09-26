<?php

declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal\Headers;
use PHPGui\Driver\Contracts\Headers\VersionInterface;

final class Downloader
{
    private static function getArchiveUrl(VersionInterface $version): string
    {
        return \vsprintf('https://github.com/raysan5/raylib/releases/download/%s/%s', [
            $version->toString(),
            self::package($version)
        ]);
    }

    private static function package(VersionInterface $version): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => \PHP_INT_SIZE === 8 ? 'raylib-' . $version->toString() . '_win64_msvc16.zip' : 'raylib-' . $version->toString() . '_win32_msvc16.zip',
            'Linux', 'BSD' => 'raylib-' . $version->toString() . '_linux_amd64.tar.gz',
            'Darwin' => throw new \LogicException('Could not download MacOS binaries, please install it manually'),
        };
    }

    /**
     * @param VersionInterface $version
     * @return string
     */
    private static function getArchiveTemp(VersionInterface $version): string
    {
        return \sys_get_temp_dir() . '/raylib-headers-' . $version->toString() . '.zip';
    }

    /**
     * @param VersionInterface $version
     * @return string
     */
    private static function downloadArchive(VersionInterface $version): string
    {
        $urlFrom = self::getArchiveUrl($version);
        $urlTo = self::getArchiveTemp($version);

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
    private static function readArchive(VersionInterface $version): iterable
    {
        return new \RecursiveIteratorIterator(new \PharData(
            self::downloadArchive($version)
        ));
    }

    /**
     * @param VersionInterface $version
     * @param non-empty-string $directory
     * @return void
     */
    public static function download(VersionInterface $version, string $directory): void
    {
        $directory = $directory . '/' . $version->toString();

        if (!\is_dir($directory)) {
            \mkdir($directory, 0777, true);
        }

        foreach (self::readArchive($version) as $file) {
            if ($file->isFile() && ((\str_contains($file->getPathname(), 'include') && \str_ends_with($file->getPathname(), '.h')) || $file->getFilename() == 'SDL_ttf.h' )) {
                $name = \pathinfo($file->getPathname(), \PATHINFO_BASENAME);

                \file_put_contents($directory . '/' . $name, $file->getContent());
            }
        }
    }
}
