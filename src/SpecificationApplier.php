<?php

namespace VolodymyrKlymniuk\DoctrineSpecification;

use Doctrine\ORM\QueryBuilder;

class SpecificationApplier
{
    /**
     * @todo replace to some builder class for building Doctrine Query Builder
     *
     * @param SpecificationInterface     $specification
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder
     * @param string                     $alias
     */
    public static function apply(
        SpecificationInterface $specification,
        QueryBuilder $queryBuilder,
        string $alias = null
    ) {
        $modifiers = $specification->getQueryModifiers();
        $visitors = $specification->getVisitors();

        if (null === $alias) {
            $alias = $queryBuilder->getRootAliases()[0];
        }

        foreach ($visitors as $visitor) {
            $visitor->visit($specification);
        }

        //expressions
        if ($where = $specification->getWhereExpression()) {
            $queryBuilder->where($where->getExpr($queryBuilder, $alias));
        }

        foreach ((array) $modifiers as $modifier) {
            $modifier->modify($queryBuilder, $alias);
        }
    }
}