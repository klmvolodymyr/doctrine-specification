<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer;

interface ResultTransformerInterface
{
    /**
     * @param array|mixed $result
     */
    public function transform($result);
}