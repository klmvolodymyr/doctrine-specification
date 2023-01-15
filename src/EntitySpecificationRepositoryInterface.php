<?php

namespace VolodymyrKlymniuk\DoctrineSpecification;

use Doctrine\Common\Collections\Selectable;
//use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use VolodymyrKlymniuk\DoctrineSpecification\ResultModifier\ResultModifierInterface;
use VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer\ResultTransformerInterface;

/**
 * This interface should be used by an EntityRepository implementing the Specification pattern.
 */
interface EntitySpecificationRepositoryInterface
{
    /**
     * Get results when you match with a Specification.
     *
     * @param SpecificationInterface     $specification
     * @param ResultTransformerInterface $resultTransformer
     * @param ResultModifierInterface    $resultModifier
     *
     * @return LazySpecificationCollection
     */
    public function match(
        SpecificationInterface $specification,
        ResultTransformerInterface $resultTransformer = null,
        ResultModifierInterface $resultModifier = null
    );

    /**
     * Get single result when you match with a Specification.
     *
     * @param SpecificationInterface     $specification
     * @param ResultTransformerInterface $transformer
     * @param ResultModifierInterface    $modifier
     *
     * @throw \NonUniqueException  If more than one result is found
     * @throw \NoResultException   If no results found
     */
    public function matchSingleResult(
        SpecificationInterface $specification,
        ResultTransformerInterface $transformer = null,
        ResultModifierInterface $modifier = null
    );

    /**
     * Get single result or null when you match with a Specification.
     *
     * @param SpecificationInterface     $specification
     * @param ResultTransformerInterface $transformer
     * @param ResultModifierInterface    $modifier
     *
     * @throw Exception\NonUniqueException  If more than one result is found
     */
    public function matchOneOrNullResult(
        SpecificationInterface $specification,
        ResultTransformerInterface $transformer = null,
        ResultModifierInterface $modifier = null
    );

    /**
     * Get doctrine query for execution
     */
    public function getQuery(SpecificationInterface $specification, ResultModifierInterface $modifier = null): Query;

    /**
     * Get query builder with applied specification
     */
    public function getQueryBuilder(SpecificationInterface $specification): QueryBuilder;
}