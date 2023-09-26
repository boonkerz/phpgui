<?php
declare(strict_types=1);

namespace PHPGui\Driver\SDL\Internal\Headers;

use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Contracts\Preprocessor\PreprocessorInterface;
use FFI\Preprocessor\Preprocessor;
use PHPGui\Driver\Contracts\Headers\HeaderInterface;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\SDL\Internal\Type;

class SDL_TTF implements HeaderInterface
{

    private const HEADERS_DIRECTORY = __DIR__ . '/../../../../../resources/headers/sdl_ttf';

    private const SDL_H = <<<'CPP'
        #ifndef SDL_h_
            #define SDL_h_
            typedef int SDL_bool;
            typedef uint8_t Uint8;
            typedef uint16_t Uint16;
            typedef uint32_t Uint32;

            typedef void* SDL_RWops;
            typedef void* SDL_version;
            typedef struct SDL_PixelFormat SDL_PixelFormat;
            typedef struct SDL_BlitMap SDL_BlitMap;

            typedef struct SDL_Surface {
                Uint32 flags;
                SDL_PixelFormat *format;
                int w, h;
                int pitch;
                void *pixels;
                void *userdata;
                int locked;
                void *list_blitmap;
                struct SDL_Rect {
                    int x, y;
                    int w, h;
                } clip_rect;
                SDL_BlitMap *map;
                int refcount;
            } SDL_Surface;

            typedef struct SDL_Color {
                Uint8 r;
                Uint8 g;
                Uint8 b;
                Uint8 a;
            } SDL_Color;
           
        #endif
        CPP;

    public function __construct(
        public readonly PreprocessorInterface $pre,
        public readonly VersionInterface $version = Version::LATEST,
    ) {
        if (!$this->exists()) {
            Downloader::download($this->version, self::HEADERS_DIRECTORY, Type::SDL_TTF);

            if (!$this->exists()) {
                throw new \RuntimeException('Could not initialize (download) header files');
            }
        }
    }

    private function exists(): bool
    {
        return \is_file($this->getHeaderPathname());
    }

    public function getHeaderPathname(): string
    {
        return self::HEADERS_DIRECTORY . '/' . $this->version->toString() . '/SDL_ttf.h';
    }

    /**
     * @param Platform|null $platform
     * @param VersionInterface|non-empty-string $version
     * @param PreprocessorInterface $pre
     * @return self
     * @throws DirectiveDefinitionExceptionInterface
     */
    public static function create(
        Platform $platform = null,
        VersionInterface|string $version = Version::LATEST,
        PreprocessorInterface $pre = new Preprocessor(),
    ): self {
        $pre = clone $pre;
        $pre->add('SDL.h', self::SDL_H, true);
        $pre->define('DECLSPEC', '');
        $pre->define('SDLCALL', '');

        $pre->add('SDL_version.h', '');
        $pre->add('begin_code.h', '');
        $pre->add('close_code.h', '');


        if (!$version instanceof VersionInterface) {
            $version = Version::create($version);
        }

        return new self($pre, $version);
    }

    public function __toString(): string
    {
        $result = $this->pre->process(new \SplFileInfo($this->getHeaderPathname())) . \PHP_EOL;

        return $result;
    }
}
