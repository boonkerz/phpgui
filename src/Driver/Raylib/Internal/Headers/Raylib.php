<?php

declare(strict_types=1);

namespace PHPGui\Driver\Raylib\Internal\Headers;

use FFI\Contracts\Preprocessor\Exception\DirectiveDefinitionExceptionInterface;
use FFI\Contracts\Preprocessor\Exception\PreprocessorExceptionInterface;
use FFI\Contracts\Preprocessor\PreprocessorInterface;
use FFI\Preprocessor\Preprocessor;
use PHPGui\Driver\Contracts\Headers\HeaderInterface;
use PHPGui\Driver\Contracts\Headers\VersionInterface;

class Raylib implements HeaderInterface
{
    /**
     * @var non-empty-string
     */
    private const HEADERS_DIRECTORY = __DIR__ . '/../../../../../resources/headers/raylib';

    /**
     * @var non-empty-string
     */
    private const SDLINC_H = <<<'CPP'
    
    CPP;


    /**
     * @param PreprocessorInterface $pre
     * @param VersionInterface $version
     */
    public function __construct(
        public readonly PreprocessorInterface $pre,
        public readonly VersionInterface $version = Version::LATEST,
    ) {
        if (!$this->exists()) {
            Downloader::download($this->version, self::HEADERS_DIRECTORY);

            if (!$this->exists()) {
                throw new \RuntimeException('Could not initialize (download) header files');
            }
        }
    }

    /**
     * @return bool
     */
    private function exists(): bool
    {
        return \is_file($this->getHeaderPathname());
    }

    /**
     * @return non-empty-string
     */
    public function getHeaderPathname(): string
    {
        return self::HEADERS_DIRECTORY . '/' . $this->version->toString() . '/raylib.h';
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

        $pre->add('stdarg.h', self::SDLINC_H);
        $pre->add('stdbool.h', '');

        if (!$version instanceof VersionInterface) {
            $version = Version::create($version);
        }

        return new self($pre, $version);
    }

    /**
     * @return non-empty-string
     * @throws PreprocessorExceptionInterface
     */
    public function __toString(): string
    {
        $result = $this->pre->process(new \SplFileInfo($this->getHeaderPathname())) . \PHP_EOL;

        $result = $this->withoutMainFunction($result);
        $result = $this->withoutStaticInline($result);

        return $result;
    }

    /**
     * @param string $result
     * @return string
     */
    private function withoutMainFunction(string $result): string
    {
        $from = [
            'extern  int SDL_main(int argc, char *argv[]);',
            'extern   int SDL_main(int argc, char *argv[]);',
        ];

        return \str_replace($from, '', $result);
    }

    /**
     * @param string $result
     * @return string
     */
    private function withoutStaticInline(string $result): string
    {
        while (($offset = \strpos($result, 'static inline')) !== false) {
            $to = $from = $offset;
            $depth = 0;

            do {
                switch ($result[$to]) {
                    case ';':
                        if ($depth === 0) {
                            $result = \substr($result, 0, $from)
                                . \substr($result, $to + 1);
                            continue 3;
                        }
                        break;

                    case '{':
                        $depth++;
                        break;

                    case '}':
                        $depth--;
                        if ($depth <= 0) {
                            while ($result[$to + 1] === ';') {
                                $to++;
                            }

                            $result = \substr($result, 0, $from)
                                . \substr($result, $to + 1);
                            continue 3;
                        }
                        break;
                }
            } while (isset($result[$to++]));
        }

        return $result;
    }
}
