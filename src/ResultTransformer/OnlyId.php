<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer;

class OnlyId extends OnlyKey
{
    const ID_KEY = 'id';

    /**
     * @param string $key
     */
    public function __construct(string $key = self::ID_KEY)
    {
        parent::__construct($key);
    }
}