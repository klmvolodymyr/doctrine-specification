<?php

namespace VolodymyrKlymniuk\DoctrineSpecification;

use VolodymyrKlymniuk\DoctrineSpecification\Expr\CompositeExpression;
use VolodymyrKlymniuk\DoctrineSpecification\Expr\ExpressionBuilder;
use VolodymyrKlymniuk\DoctrineSpecification\Expr\ExpressionInterface;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\Count;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\GroupBy;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\Having;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\InnerJoin;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\LeftJoin;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\Limit;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\Offset;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\OrderBy;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\QueryModifierInterface;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\Select;
use VolodymyrKlymniuk\DoctrineSpecification\Visitor\VisitorInterface;

class Specification implements SpecificationInterface
{
    /**
     * @var QueryModifierInterface[]
     */
    private $queryModifiers = [];

    /**
     * @var ExpressionInterface
     */
    private $expression;

    /**
     * @var VisitorInterface[]
     */
    private $visitors = [];

    /**
     * @var ExpressionBuilder
     */
    private static $expressionBuilder;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Returns the expression builder.
     *
     * @return ExpressionBuilder
     */
    public static function expr()
    {
        if (self::$expressionBuilder === null) {
            self::$expressionBuilder = new ExpressionBuilder();
        }

        return self::$expressionBuilder;
    }

    /**
     * Adds to the query builder select construction
     *
     * @param string $select
     *
     * @return $this
     */
    public function select(string $select)
    {
        $key = \sprintf('select_%s', $select);
        if (false === isset($this->queryModifiers[$key])) {
            $this->queryModifiers[$key] = new Select($select);
        }

        return $this;
    }

    /**
     * Sets the where expression to evaluate when this Specification is searched for.
     *
     * @param ExpressionInterface $expression
     *
     * @return $this
     */
    public function where(ExpressionInterface $expression)
    {
        $this->expression = $expression;

        return $this;
    }

    /**
     * Appends the where expression to evaluate when this Specification is searched for
     * using an AND with previous expression.
     *
     * @param ExpressionInterface $expression
     *
     * @return $this
     */
    public function andWhere(ExpressionInterface $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }

        $this->expression = new CompositeExpression(CompositeExpression::TYPE_AND, [
            $this->expression,
            $expression,
        ]);

        return $this;
    }

    /**
     * Appends the where expression to evaluate when this Specification is searched for
     * using an OR with previous expression.
     *
     * @param ExpressionInterface $expression
     *
     * @return $this
     */
    public function orWhere(ExpressionInterface $expression)
    {
        if ($this->expression === null) {
            return $this->where($expression);
        }

        $this->expression = new CompositeExpression(CompositeExpression::TYPE_OR, [
            $this->expression,
            $expression,
        ]);

        return $this;
    }

    /**
     * Gets the expression attached to this Specification.
     *
     * @return ExpressionInterface|null
     */
    public function getWhereExpression()
    {
        return $this->expression;
    }

    /**
     * Gets the expression attached to this Specification.
     *
     * @return QueryModifierInterface[]
     */
    public function getQueryModifiers()
    {
        return $this->queryModifiers;
    }

    public function leftJoin(string $field, string $newAlias, string $dqlAlias = null)
    {
        return $this->join(LeftJoin::class, $field, $newAlias, $dqlAlias);
    }

    public function innerJoin(string $field, string $newAlias, string $dqlAlias = null, string $condition = null)
    {
        return $this->join(InnerJoin::class, $field, $newAlias, $dqlAlias, $condition);
    }

    public function limit(int $count)
    {
        $this->queryModifiers[] = new Limit($count);

        return $this;
    }

    public function offset(int $count)
    {
        $this->queryModifiers[] = new Offset($count);

        return $this;
    }

    public function orderBy(string $field, string $order = 'ASC', string $dqlAlias = null)
    {
        $key = \sprintf('order_%s', $field);
        $this->queryModifiers[$key] = new OrderBy($field, $order, $dqlAlias);

        return $this;
    }

    public function groupBy(string $field, string $dqlAlias = null)
    {
        $key = \sprintf('group_%s', $field);
        $this->queryModifiers[$key] = new GroupBy($field, $dqlAlias);

        return $this;
    }

    public function having(ExpressionInterface $expression)
    {
        $key = \sprintf('having_%s', $expression);
        $this->queryModifiers[$key] = new Having($expression);

        return $this;
    }

    public function count(string $field = 'id', string $asName = null, string $dqlAlias = null)
    {
        $key = \sprintf('count_%s', $field);
        $this->queryModifiers[$key] = new Count($field, $asName, $dqlAlias);

        return $this;
    }

    public function addVisitor(VisitorInterface $visitor)
    {
        $this->visitors[] = $visitor;

        return $this;
    }

    public function getVisitors(): array
    {
        return $this->visitors;
    }

    public function mergeQueryModifiers(array $queryModifiers): SpecificationInterface
    {
        foreach ($queryModifiers as $key => $queryModifier) {
            if (!($queryModifier instanceof QueryModifierInterface)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Query modifier MUST implement %s',
                    QueryModifierInterface::class
                ));
            }
            $this->queryModifiers[$key] = $queryModifier;
        }

        return $this;
    }

    private function join(
        string $type,
        string $field,
        string $newAlias,
        string $dqlAlias = null,
        string $condition = null
    ) {
        $key = \sprintf('join_%s', $field);
        if (true === isset($this->queryModifiers[$key])) {
            throw new \InvalidArgumentException(sprintf('Join for field "%s" is alredy exist', $field));
        }
        $this->queryModifiers[$key] = new $type($field, $newAlias, $dqlAlias, $condition);

        return $this;
    }
}