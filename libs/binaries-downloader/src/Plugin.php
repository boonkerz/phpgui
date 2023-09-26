<?php

namespace Local\BinariesDownloader;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

final class Plugin implements PluginInterface
{

    public function activate(Composer $composer, IOInterface $io): void
    {
        $sdl = new SDL();
        $sdl->activate($composer, $io);
        $sdlTtf = new SDL_ttf();
        $sdlTtf->activate($composer, $io);
        $sdlImage = new SDL_image();
        $sdlImage->activate($composer, $io);
        $raylib = new Raylib();
        $raylib->activate($composer, $io);
    }

    public function deactivate(Composer $composer, IOInterface $io): void
    {
        $sdl = new SDL();
        $sdl->deactivate($composer, $io);
        $sdlTtf = new SDL_ttf();
        $sdlTtf->deactivate($composer, $io);
        $sdlImage = new SDL_image();
        $sdlImage->deactivate($composer, $io);
        $raylib = new Raylib();
        $raylib->deactivate($composer, $io);
    }

    public function uninstall(Composer $composer, IOInterface $io): void
    {
        $sdl = new SDL();
        $sdl->uninstall($composer, $io);
        $sdlTtf = new SDL_ttf();
        $sdlTtf->uninstall($composer, $io);
        $sdlImage = new SDL_image();
        $sdlImage->uninstall($composer, $io);
        $raylib = new Raylib();
        $raylib->uninstall($composer, $io);
    }
}