<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\QueryModifier;

/**
 * Abstract expression
 */
abstract class AbstractQueryModifier implements QueryModifierInterface
{
    protected $dqlAlias;

    /**
     * {@inheritDoc}
     */
    public function getDqlAlias(): string
    {
        return (string) $this->dqlAlias;
    }

    /**
     * {@inheritDoc}
     */
    public function setDqlAlias(string $alias): QueryModifierInterface
    {
        $this->dqlAlias = $alias;

        return $this;
    }
}