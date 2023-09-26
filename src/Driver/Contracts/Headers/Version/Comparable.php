<?php

declare(strict_types=1);

namespace PHPGui\Driver\Contracts\Headers\Version;

use PHPGui\Driver\Contracts\Headers\Version;
use PHPGui\Driver\Contracts\Headers\VersionInterface;
use PHPGui\Driver\SDL\Contracts\Headers\Version\VersionStringType;

trait Comparable
{
    /**
     * @param VersionInterface|VersionStringType $version
     * @return VersionInterface
     */
    private function make(VersionInterface|string $version): VersionInterface
    {
        if ($version instanceof VersionInterface) {
            return $version;
        }

        return Version::fromString($version);
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function eq(VersionInterface|string $version): bool
    {
        return \version_compare($this->toString(), $this->make($version)->toString(), '=');
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function neq(VersionInterface|string $version): bool
    {
        return \version_compare($this->toString(), $this->make($version)->toString(), '<>');
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function gt(VersionInterface|string $version): bool
    {
        return \version_compare($this->toString(), $this->make($version)->toString(), '>');
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function gte(VersionInterface|string $version): bool
    {
        return \version_compare($this->toString(), $this->make($version)->toString(), '>=');
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function lt(VersionInterface|string $version): bool
    {
        return \version_compare($this->toString(), $this->make($version)->toString(), '<');
    }

    /**
     * {@inheritDoc}
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function lte(VersionInterface|string $version): bool
    {
        return \version_compare($this->toString(), $this->make($version)->toString(), '<=');
    }

    /**
     * {@inheritDoc}
     */
    abstract public function toString(): string;
}
