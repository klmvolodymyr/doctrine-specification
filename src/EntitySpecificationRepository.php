<?php

namespace VolodymyrKlymniuk\DoctrineSpecification;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use VolodymyrKlymniuk\DoctrineSpecification\ResultModifier\ResultModifierInterface;
use VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer\ResultTransformerInterface;

/**
 * This class allows you to use a Specification to query entities.
 */
class EntitySpecificationRepository  extends EntityRepository implements EntitySpecificationRepositoryInterface
{
    /**
     * @var string alias
     */
    private $alias = 'e';

    /**
     * {@inheritDoc}
     */
    public function match(
        SpecificationInterface $specification,
        ResultTransformerInterface $resultTransformer = null,
        ResultModifierInterface $resultModifier = null
    ) {
        return new LazySpecificationCollection($this, $specification, $resultModifier, $resultTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function matchSingleResult(
        SpecificationInterface $specification,
        ResultTransformerInterface $transformer = null,
        ResultModifierInterface $modifier = null
    ) {
        $result = $this->getQuery($specification, $modifier)->getSingleResult();

        if ($transformer instanceof ResultTransformerInterface) {
            $result = $transformer->transform($result);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function matchOneOrNullResult(
        SpecificationInterface $specification,
        ResultTransformerInterface $transformer = null,
        ResultModifierInterface $modifier = null
    ) {
        try {
            return $this->matchSingleResult($specification, $transformer, $modifier);
        } catch (NoResultException $e) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getQueryBuilder(
        SpecificationInterface $specification
    ): QueryBuilder {
        $queryBuilder = $this->createQueryBuilder($this->alias);
        //apply specification to the query builder
        SpecificationApplier::apply(clone $specification, $queryBuilder, $this->getAlias());

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery(
        SpecificationInterface $specification,
        ResultModifierInterface $modifier = null
    ): Query {
        $queryBuilder = $this->getQueryBuilder($specification);
        $query = $queryBuilder->getQuery();

        if ($modifier !== null) {
            $modifier->modify($query);
        }

        return $query;
    }

    public function setAlias(string $alias)
    {
        $this->alias = $alias;

        return $this;
    }

    public function getAlias(): string
    {
        return $this->alias;
    }
}