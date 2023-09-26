<?php
declare(strict_types=1);

namespace PHPGui\Application;

trait InteractWithPaths
{
    private function formatPath(string $path): string
    {
        return \rtrim(\str_replace('\\', '/', $path), '/');
    }

    public function path(string $path = ''): string
    {
        return $this->formatPath($this['path'] . '/' . $path);
    }

    public function resourcesPath(string $path = ''): string
    {
        return $this->formatPath($this['path.resources'] . '/' . $path);
    }

    public function configPath(string $path = ''): string
    {
        return $this->formatPath($this['path.config'] . '/' . $path);
    }

    /**
     * @param string $path
     * @return string
     */
    public function storagePath(string $path = ''): string
    {
        return $this->formatPath($this['path.storage'] . '/' . $path);
    }

    private function registerPaths(string $path): void
    {
        $path = $this->formatPath($path);

        $this->instance('path', $path);
        $this->instance('path.resources', $path . '/Resources');
        $this->instance('path.config', $path . '/Config');
        $this->instance('path.storage', $path . '/Storage');
        $this->instance('path.controllers', $path . '/Controllers');
        $this->instance('path.windows', $path . '/Windows');
    }
}
