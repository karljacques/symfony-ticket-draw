<?php

namespace Domain\Entity;

class Ticket
{
    public function __construct(protected string $entrantEmail, protected \DateTimeImmutable $createdAt)
    {
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getEntrantEmail(): string
    {
        return $this->entrantEmail;
    }


}
