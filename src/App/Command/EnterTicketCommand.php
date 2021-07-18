<?php

namespace App\Command;

use App\CQRS\Bus\CommandBus;
use App\CQRS\Command\EnterTicketCommand\EnterTicketCommand as CQRSEnterTicketCommand;
use App\Service\ClockInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnterTicketCommand extends Command
{
    protected static $defaultName = 'draw:enter';

    public function __construct(
        protected ClockInterface $clock,
        protected CommandBus $bus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('drawId', InputArgument::REQUIRED, 'Draw Identifier')
            ->addArgument('email', InputArgument::REQUIRED, 'Entrant Email Address');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Ideally I'd do some better validation here, maybe a service/factory to centralise this
        // but I'm running out of time so it's quick.
        $email = $input->getArgument('email');
        $drawId = $input->getArgument('drawId');

        if (!is_string($email)) {
            $output->writeln('<error>Email must be string</error>');

            return Command::FAILURE;
        }

        if (!is_numeric($drawId)) {
            $output->writeln('<error>drawId must be integer</error>');

            return Command::FAILURE;
        }

        $command = new CQRSEnterTicketCommand(intval($drawId), $email, $this->clock->now());

        $this->bus->dispatch($command);

        return Command::SUCCESS;
    }
}
