<?php

namespace VolodymyrKlymniuk\DoctrineSpecification\ResultModifier;

use Doctrine\ORM\AbstractQuery;

interface ResultModifierInterface
{
    public function modify(AbstractQuery $query);
}