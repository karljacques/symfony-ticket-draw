<?php

namespace App\Integration\Command;

use App\Service\ClockInterface;
use Domain\Entity\Draw;
use Domain\Entity\Ticket;
use Domain\Repository\DrawRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Loader\Configurator\Traits\PropertyTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class EnterTicketCommandTest extends KernelTestCase
{
    use PropertyTrait;

    protected Draw $draw;
    protected MessageBusInterface $bus;

    public function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->draw = Draw::create('playstation', 2);

        /** @var DrawRepository $drawRepository */
        $drawRepository = self::$container->get(DrawRepository::class);
        $drawRepository->save($this->draw);

        /** @var MessageBusInterface $bus */
        $bus = self::$container->get('command.bus');
        $this->bus = $bus;
    }

    public function testAddTicketToDraw(): void
    {
        $email = 'bob@exampledomain.com';
        $time = new \DateTimeImmutable();

        $clock = $this->prophesize(ClockInterface::class);
        $clock->now()->willReturn($time);

        self::$container->set(ClockInterface::class, $clock->reveal());

        $drawId = $this->draw->getId();
        $this->assertNotNull($drawId);

        $application = new Application(self::$kernel);
        $command = $application->find('draw:enter');

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
            'drawId' => $drawId,
            'email' => $email,
        ]);

        $ticket = $this->draw->getTickets()->first();
        $this->assertInstanceOf(Ticket::class, $ticket);

        $this->assertEquals($email, $ticket->getEntrantEmail());
        $this->assertEquals($time, $ticket->getCreatedAt());
    }
}
