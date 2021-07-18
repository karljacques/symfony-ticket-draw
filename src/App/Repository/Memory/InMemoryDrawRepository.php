<?php

namespace App\Repository\Memory;

use App\Exception\EntityNotFoundException;
use Domain\Entity\Draw;
use Domain\Repository\DrawRepository;

class InMemoryDrawRepository implements DrawRepository
{
    /** @var array<int, Draw> */
    private array $draws = [];

    public function find(int $id): Draw
    {
        // Kept the original interface - I'd return null here usually.
        if (!isset($this->draws[$id])) {
            throw new EntityNotFoundException();
        }

        return $this->draws[$id];
    }

    public function findClosestToTarget(): \Iterator
    {
        return new \ArrayIterator([]);
    }

    // In-memory save will only have an effect when "persisting" a new entity, otherwise
    // array object is the same object as elsewhere and will already be saved
    //
    // Persisting a new entity to the array involves generating a new identifier and assigning it via reflection, similar to
    // how it's done in Doctrine.
    public function save(Draw $draw): void
    {
        $drawId = $draw->getId();

        if ($drawId === null) {
            $drawId = $this->getNextDrawId();

            $class = new \ReflectionClass(Draw::class);
            $protectedIdentifier = $class->getProperty('id');
            $protectedIdentifier->setAccessible(true);

            $protectedIdentifier->setValue($draw, $drawId);
        }

        $this->draws[$drawId] = $draw;
    }

    private function getNextDrawId(): int
    {
        if (count($this->draws) === 0) {
            return 1;
        }

        return max(array_keys($this->draws)) + 1;
    }
}
