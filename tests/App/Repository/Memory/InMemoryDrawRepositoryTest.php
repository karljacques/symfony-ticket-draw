<?php

namespace App\Tests\Repository\Memory;

use App\Repository\Memory\InMemoryDrawRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Domain\Entity\Draw;
use PHPUnit\Framework\TestCase;

class InMemoryDrawRepositoryTest extends TestCase
{
    protected InMemoryDrawRepository $repository;

    public function setUp(): void
    {
        $this->repository = new InMemoryDrawRepository();
    }

    public function testSaveNewRepository(): void
    {
        $draw = $this->createDraw(null);
        $this->assertNull($draw->getId());

        // persist
        $this->repository->save($draw);

        // Get id
        $id = $draw->getId();
        $this->assertNotNull($id);
        $this->assertEquals(1, $id);

        $this->assertEquals($draw, $this->repository->find($id));
    }

    protected function createDraw(?int $identifier): Draw
    {
        return new Draw(
            $identifier,
            'playstation',
            20,
            new ArrayCollection([]),
            new \DateTimeImmutable(),
        );
    }
}
