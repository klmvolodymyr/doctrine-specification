<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\Expr;

class GreaterOrEqualThan extends Comparison
{
    /**
     * @param string      $field
     * @param mixed       $value
     * @param string|null $dqlAlias
     */
    public function __construct(string $field, $value, string $dqlAlias = null)
    {
        parent::__construct(self::GTE, $field, $value, $dqlAlias);
    }
}