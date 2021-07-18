<?php

namespace App\CQRS\Command\EnterTicketCommand;

use App\CQRS\Command\Command;

class EnterTicketCommand implements Command
{
    public function __construct(
        protected int $drawId,
        protected string $entrantEmailAddress,
        protected \DateTimeImmutable $entryTime
    ) {
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEntryTime(): \DateTimeImmutable
    {
        return $this->entryTime;
    }

    public function getDrawId(): int
    {
        return $this->drawId;
    }

    public function getEntrantEmailAddress(): string
    {
        return $this->entrantEmailAddress;
    }
}
