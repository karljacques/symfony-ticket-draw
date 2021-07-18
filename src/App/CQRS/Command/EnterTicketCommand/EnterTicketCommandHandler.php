<?php

namespace App\CQRS\Command\EnterTicketCommand;

use App\CQRS\CommandHandlerInterface;
use Domain\Entity\Ticket;
use Domain\Repository\DrawRepository;

final class EnterTicketCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        protected DrawRepository $drawRepository,
    ) {
    }

    public function __invoke(EnterTicketCommand $command): int
    {
        // Check draw exists
        $draw = $this->drawRepository->find($command->getDrawId());

        // Enter ticket
        $ticket = new Ticket($command->getEntrantEmailAddress(), $command->getEntryTime());
        $draw->enterTicketToDraw($ticket);

        // Persist
        $this->drawRepository->save($draw);

        return 0;
    }
}
