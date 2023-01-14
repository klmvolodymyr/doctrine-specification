<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\Expr;

abstract class AbstractExpr  implements ExpressionInterface
{
    /**
     * @var string
     */
    protected $dqlAlias;

    /**
     * {@inheritdoc}
     */
    public function getDqlAlias(): string
    {
        return (string) $this->dqlAlias;
    }

    /**
     * {@inheritdoc}
     */
    public function setDqlAlias(string $alias): ExpressionInterface
    {
        $this->dqlAlias = $alias;

        return $this;
    }
}