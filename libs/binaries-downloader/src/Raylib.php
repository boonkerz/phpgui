<?php

declare(strict_types=1);

namespace Local\BinariesDownloader;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

final class Raylib implements PluginInterface
{
    private const VENDOR  = 'raysan5';
    private const PACKAGE = 'raylib';
    private const VERSION = '4.5.0';

    /**
     * @param non-empty-string $name
     *
     * @return non-empty-string
     */
    private function urlTo(string $name): string
    {
        return \vsprintf('https://github.com/%s/%s/releases/download/%s/%s', [
            self::VENDOR,
            self::PACKAGE,
            self::VERSION,
            $name
        ]);
    }

    /**
     * @return non-empty-string
     */
    private function package(): string
    {
        return match (\PHP_OS_FAMILY) {
            'Windows' => \PHP_INT_SIZE === 8 ? 'raylib-' . self::VERSION . '_win64_msvc16.zip' : 'raylib-' . self::VERSION . '_win32_msvc16.zip',
            'Linux', 'BSD' => 'raylib-' . self::VERSION . '_linux_amd64.tar.gz',
            'Darwin' => throw new \LogicException('Could not download MacOS binaries, please install it manually'),
        };
    }

    private function getInputPathname(): string
    {
        return $this->urlTo($this->getOutputFilename());
    }

    private function getBinDirectory(Composer $composer): string
    {
        $config = $composer->getConfig();

        return $config->get('bin-dir');
    }

    private function getOutputPathname(Composer $composer): string
    {
        return $this->getBinDirectory($composer) . '/'
            . $this->getOutputFilename();
    }

    private function getOutputFilename(): string
    {
        return $this->package();
    }

    private function getOutputDirectory(Composer $composer): string
    {
        return $this->getBinDirectory($composer);
    }

    public function activate(Composer $composer, IOInterface $io): void
    {
        echo "activate";
        try {
            $this->download($composer, $io);
            $this->extract($composer, $io);
        } catch (\Throwable $e) {
            $io->error($e->getMessage());
        }
    }

    private function download(Composer $composer, IOInterface $io): void
    {
        $input = $this->getInputPathname();
        $output = $this->getOutputPathname($composer);

        if (\is_file($output)) {
            return;
        }

        $io->write('<info>- Downloading:</info> ' . $input);

        \copy($input, $output);
    }

    private function extract(Composer $composer, IOInterface $io): void
    {
        $filesystem = new Filesystem();

        $phar = new \PharData($this->getOutputPathname($composer));
        $output = $this->getOutputDirectory($composer) ;
        $filesystem->mkdir($output. '/temp');
        $phar->extractTo($output. '/temp');

        $finder = new Finder();
        $finder->name('raylib.dll');
        foreach($finder->in($output. '/temp') as $file) {
            copy($file->getPathname(), $output. '/' . $file->getFilename());
        }


        $filesystem->remove([$this->getOutputPathname($composer), $output. '/temp']);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
    }
}
