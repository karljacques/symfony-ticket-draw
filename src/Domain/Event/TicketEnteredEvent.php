<?php

namespace Domain\Event;

use Domain\Entity\Ticket;

class TicketEnteredEvent implements DomainEventInterface
{
    public function __construct(protected Ticket $ticket)
    {
    }

    public function getTicket(): Ticket
    {
        return $this->ticket;
    }
}
