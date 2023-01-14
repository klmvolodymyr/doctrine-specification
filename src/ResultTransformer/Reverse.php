<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Reverse implements ResultTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($result)
    {
        return array_reverse($result);
    }
}