<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer;

use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Fetch from the result only values with key which pass as constructor parameter
 */
class OnlyKey implements ResultTransformerInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @param string $key
     */
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($result)
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $ids = [];
        foreach ($result as &$item) {
            $ids[] = $propertyAccessor->getValue($item, $this->key);
        }

        return $ids;
    }
}
