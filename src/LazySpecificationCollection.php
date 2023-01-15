<?php

namespace VolodymyrKlymniuk\DoctrineSpecification;

use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use VolodymyrKlymniuk\DoctrineSpecification\QueryModifier\QueryModifierInterface;
use VolodymyrKlymniuk\DoctrineSpecification\ResultModifier\ResultModifierInterface;
use VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer\ResultTransformerInterface;

/**
 * Class LazySpecificationCollection
 */
class LazySpecificationCollection extends AbstractLazyCollection
{
    /**
     * @var EntitySpecificationRepository
     */
    private $repository;

    /**
     * @var SpecificationInterface
     */
    private $specification;

    /**
     * @var QueryModifierInterface
     */
    private $resultModifier;

    /**
     * @var ResultTransformerInterface
     */
    private $resultTransformer;

    public function __construct(
        EntitySpecificationRepository $repository,
        SpecificationInterface $specification,
        ResultModifierInterface $resultModifier = null,
        ResultTransformerInterface $resultTransformer = null
    ) {
        $this->repository = $repository;
        $this->specification = $specification;
        $this->resultModifier = $resultModifier;
        $this->resultTransformer = $resultTransformer;
    }

    /**
     * @return QueryBuilder
     */
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->repository->getQueryBuilder($this->specification);
    }

    /**
     * @return EntitySpecificationRepository
     */
    public function getRepository(): EntitySpecificationRepository
    {
        return $this->repository;
    }

    /**
     * @return SpecificationInterface
     */
    public function getSpecification(): SpecificationInterface
    {
        return $this->specification;
    }

    /**
     * @return QueryModifierInterface
     */
    public function getResultModifier(): QueryModifierInterface
    {
        return $this->resultModifier;
    }

    /**
     * @return ResultTransformerInterface
     */
    public function getResultTransformer(): ?ResultTransformerInterface
    {
        return $this->resultTransformer;
    }

    /**
     * @param ResultTransformerInterface $resultTransformer
     *
     * @return LazySpecificationCollection
     */
    public function setResultTransformer($resultTransformer)
    {
        $this->resultTransformer = $resultTransformer;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function doInitialize()
    {
        $query = $this->repository->getQuery($this->specification, $this->resultModifier);
        $result = $query->execute();

        if ($this->resultTransformer instanceof ResultTransformerInterface) {
            $result = $this->resultTransformer->transform($result);
        }

        $this->collection = new ArrayCollection($result);
    }
}