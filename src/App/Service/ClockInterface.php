<?php

namespace App\Service;

/**
 * Based on PSR proposal https://github.com/php-fig/fig-standards/blob/master/proposed/clock.md.
 */
interface ClockInterface
{
    /**
     * Returns the current time as a DateTimeImmutable Object.
     */
    public function now(): \DateTimeImmutable;
}
