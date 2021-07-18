<?php

namespace Domain\Repository;

use Domain\Entity\Draw;

interface DrawRepository
{
    public function find(int $id): Draw;

    /** @return \Iterator<Draw> */
    public function findClosestToTarget(): \Iterator;

    public function save(Draw $draw): void;
}
