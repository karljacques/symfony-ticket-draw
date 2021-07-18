<?php

namespace App\Command;

use Domain\Entity\Draw;
use Domain\Repository\DrawRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDrawCommand extends Command
{
    protected static $defaultName = 'draw:create';

    public function __construct(protected DrawRepository $drawRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'Draw name')
            ->addArgument('target', InputArgument::REQUIRED, 'target');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Ideally I'd do some better validation here, maybe a service/factory to centralise this
        // but I'm running out of time so it's quick.
        $name = $input->getArgument('name');
        $target = $input->getArgument('target');

        if (!is_string($name)) {
            $output->writeln('<error>Name must be string</error>');
            return Command::FAILURE;
        }

        if (!is_numeric($target)) {
            $output->writeln('<error>Target must be integer</error>');
            return Command::FAILURE;
        }

        $draw = Draw::create($name, intval($target));

        $this->drawRepository->save($draw);

        return Command::SUCCESS;
    }
}
