<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultTransformer;

use Symfony\Component\PropertyAccess\PropertyAccess;

class Assoc implements ResultTransformerInterface
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

        $assoc = [];
        foreach ($result as &$item) {
            $assoc[$propertyAccessor->getValue($item, $this->key)] = $item;
        }

        return $assoc;
    }
}