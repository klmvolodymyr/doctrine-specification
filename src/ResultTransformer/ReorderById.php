<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer;

class ReorderById implements ResultTransformerInterface
{
    protected array $ids = [];

    /**
     * ReorderByIds constructor.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($result)
    {
        $flipped = array_flip($this->ids);
        usort($result, function ($entity1, $entity2) use ($flipped) {
            return $flipped[$entity1->getId()] <=> $flipped[$entity2->getId()];
        });

        return $result;
    }
}