<?php

namespace Domain\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Domain\Entity\Draw;
use Domain\Entity\Ticket;
use Domain\Event\TicketEnteredEvent;
use Domain\Exception\DomainLogicException;

class DrawTest extends \PHPUnit\Framework\TestCase
{
    private static \DateTimeImmutable $currentTime;
    private Draw $sut;

    public static function setUpBeforeClass(): void
    {
        self::$currentTime = new \DateTimeImmutable();
    }

    public function setUp(): void
    {
        $tickets = new ArrayCollection([
            new Ticket('bob@testmail.com', new \DateTimeImmutable('2021-05-01')),
            new Ticket('alan@testmail.com', new \DateTimeImmutable('2021-06-01')),
        ]);

        $this->sut = new Draw(null, 'playstation', 3, $tickets, self::$currentTime, new \DateTimeImmutable('2021-06-01'));
    }

    public function testDrawHasCorrectTitle(): void
    {
        $this->assertEquals('playstation', $this->sut->getTitle());
    }

    public function testDrawHasCorrectTickets(): void
    {
        $this->assertCount(2, $this->sut->getTickets());
    }

    public function testDrawHasCorrectTarget(): void
    {
        $this->assertEquals(3, $this->sut->getTarget());
    }

    public function testCanEnterTicketToDraw(): Draw
    {
        $this->sut->enterTicketToDraw(new Ticket('ryan@testmail.com', self::$currentTime));
        $this->assertCount(3, $this->sut->getTickets());

        return $this->sut;
    }

    public function testGetLastDrawAt(): void
    {
        $this->assertEquals(new \DateTime('2021-06-01'), $this->sut->getLastDrawAt());
    }

    public function testGetCreatedAt(): void
    {
        $this->assertEquals(self::$currentTime, $this->sut->getCreatedAt());
    }

    /**
     * @depends testCanEnterTicketToDraw
     */
    public function testLastDrawTimeUpdated(Draw $draw): void
    {
        $this->assertEquals(self::$currentTime, $draw->getLastDrawAt());
    }

    /**
     * @depends testCanEnterTicketToDraw
     */
    public function testTicketEnteredEventRaised(Draw $draw): void
    {
        $this->assertInstanceOf(TicketEnteredEvent::class, $draw->popEvent());
        $this->assertNull($draw->popEvent());
    }

    /**
     * @depends testCanEnterTicketToDraw
     */
    public function testCannotAddTicketWhenTargetReached(Draw $draw): void
    {
        $this->expectException(DomainLogicException::class);

        $draw->enterTicketToDraw(new Ticket('bruce@testmail.com', new \DateTimeImmutable()));
    }
}
