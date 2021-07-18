<?php

namespace App\CQRS\Bus;

use Domain\Event\DomainEventInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class EventBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function dispatch(DomainEventInterface $event): void
    {
        $this->handle($event);
    }
}
