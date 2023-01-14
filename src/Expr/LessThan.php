<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\Expr;

class LessThan extends Comparison
{
    /**
     * @param string      $field
     * @param mixed       $value
     * @param string|null $dqlAlias
     */
    public function __construct(string $field, $value, string $dqlAlias = null)
    {
        parent::__construct(self::LT, $field, $value, $dqlAlias);
    }
}