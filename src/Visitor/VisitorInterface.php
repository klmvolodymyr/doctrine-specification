<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\Visitor;

use VolodymyrKlymniuk\DoctrineSpecification\SpecificationInterface;

interface VisitorInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return void
     */
    public function visit(SpecificationInterface $specification): void;
}