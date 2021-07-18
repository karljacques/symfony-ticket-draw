<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\CQRS\Bus\EventBus;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Domain\Entity\Aggregate;
use Symfony\Component\Messenger\MessageBusInterface;

// Taken almost exactly from https://github.com/ferrius/ddd-cqrs-example/blob/6af929218b14e29890a66db2da05dd5e55f11990/src/Shared/Infrastructure/Doctrine/DomainEventSubscriber.php#L62
final class DomainEventSubscriber implements EventSubscriber
{
    /** @var Aggregate[] */
    private array $entities = [];

    public function __construct(protected EventBus $eventBus)
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
            Events::postFlush,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->keepAggregateRoots($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->keepAggregateRoots($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->keepAggregateRoots($args);
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        foreach ($this->entities as $entity) {
            while ($event = $entity->popEvent()) {
                $this->eventBus->dispatch($event);
            }
        }
    }

    private function keepAggregateRoots(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if (!($entity instanceof Aggregate)) {
            return;
        }

        $this->entities[] = $entity;
    }
}
