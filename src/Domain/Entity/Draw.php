<?php

namespace Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Domain\Event\TicketEnteredEvent;
use Domain\Exception\DomainLogicException;

class Draw extends Aggregate
{
    /**
     * @param Collection<int, Ticket> $tickets
     */
    public function __construct(
        private ?int $id,
        private string $title,
        private int $target,
        private Collection $tickets,
        private \DateTimeImmutable $createdAt,
        private ?\DateTimeImmutable $lastDrawAt = null
    ) {
    }

    public static function create(string $title, int $target): self
    {
        return new self(
            null,
            $title,
            $target,
            new ArrayCollection([]),
            new \DateTimeImmutable()
        );
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getLastDrawAt(): ?\DateTimeImmutable
    {
        return $this->lastDrawAt;
    }

    public function getTarget(): int
    {
        return $this->target;
    }

    public function enterTicketToDraw(Ticket $ticket): void
    {
        if ($this->hasSpacesRemaining() === false) {
            throw new DomainLogicException('Draw is full');
        }

        $this->tickets->add($ticket);
        $this->lastDrawAt = $ticket->getCreatedAt();

        $this->raise(new TicketEnteredEvent($ticket));
    }

    private function hasSpacesRemaining(): bool
    {
        return $this->tickets->count() < $this->target;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }
}
