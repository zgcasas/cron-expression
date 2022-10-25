<?php

namespace Cron;

use DateTimeInterface;


/**
 * Year field.  Allows: * , / -
 */
class YearField extends AbstractField
{
    public function isSatisfiedBy(DateTimeInterface $date, $value, bool $invert): bool
    {
        if ($value === '?') {
            return true;
        }

        return $this->isSatisfied($date->format('Y'), $value);
    }

    public function increment(DateTimeInterface &$date, $invert = false, $parts = null): FieldInterface
    {
        if ($invert) {
            $date->modify('-1 year');
            $date->setDate($date->format('Y'), 12, 31);
            $date->setTime(23, 59, 0);
        } else {
            $date->modify('+1 year');
            $date->setDate($date->format('Y'), 1, 1);
            $date->setTime(0, 0, 0);
        }

        return $this;
    }

    public function validate(string $value): bool
    {
        return (bool) preg_match('/^[\*,\/\-\d\?]+$/', $value);
    }
}
