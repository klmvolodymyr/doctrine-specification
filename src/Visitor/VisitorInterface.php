<?php

namespace VolodymyrKlymniuk\DoctrineSpecification;

interface VisitorInterface
{
    /**
     * @param SpecificationInterface $specification
     *
     * @return void
     */
    public function visit(SpecificationInterface $specification): void;
}