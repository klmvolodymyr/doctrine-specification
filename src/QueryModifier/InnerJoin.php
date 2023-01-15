<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\QueryModifier;

/**
 * Query INNER JOIN construction
 */
class InnerJoin extends AbstractJoin
{
    /**
     * {@inheritdoc}
     */
    protected function getJoinType(): string
    {
        return 'innerJoin';
    }
}