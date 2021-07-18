<?php


namespace Domain\Entity;


use Domain\Event\DomainEventInterface;

// Taken mostly from https://github.com/ferrius/ddd-cqrs-example/blob/6af929218b14e29890a66db2da05dd5e55f11990/src/Shared/Domain/Model/Aggregate.php#L7
abstract class Aggregate
{
    /**
     * @var DomainEventInterface[]
     */
    private array $events = [];


    public function popEvent(): ?DomainEventInterface
    {
        return array_pop($this->events);
    }

    protected function raise(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }
}
