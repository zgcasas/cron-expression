<?php

declare(strict_types=1);

namespace Cron\Tests;

use Cron\YearField;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

/**
 * @author Michael Dowling <mtdowling@gmail.com>
 */
class YearFieldTest extends TestCase
{
    /**
     * @covers \Cron\MonthField::validate
     */
    public function testValidatesField(): void
    {
        $f = new YearField();
        $this->assertTrue($f->validate('2022'));
        $this->assertTrue($f->validate('*'));
    }

    /**
     * @covers \Cron\MonthField::isSatisfiedBy
     */
    public function testChecksIfSatisfied(): void
    {
        $f = new YearField();
        $this->assertTrue($f->isSatisfiedBy(new DateTime(), '?', false));
        $this->assertTrue($f->isSatisfiedBy(new DateTimeImmutable(), '?', false));
    }

    /**
     * @covers \Cron\MonthField::increment
     */
    public function testIncrementsDate(): void
    {
        $d = new DateTime('2011-03-15 11:15:00');
        $f = new YearField();
        $f->increment($d);
        $this->assertSame('2012-01-01 00:00:00', $d->format('Y-m-d H:i:s'));

        $d = new DateTime('2011-03-15 11:15:00');
        $f->increment($d, true);
        $this->assertSame('2010-12-31 23:59:00', $d->format('Y-m-d H:i:s'));
    }

    /**
     * @covers \Cron\MonthField::increment
     */
    public function testIncrementsDateTimeImmutable(): void
    {
        $d = new DateTimeImmutable('2011-03-15 11:15:00');
        $f = new YearField();
        $f->increment($d);
        $this->assertSame('2011-03-15 11:15:00', $d->format('Y-m-d H:i:s'));
    }

    /**
     * @covers \Cron\MonthField::increment
     */
    public function testIncrementsDateWithThirtyMinuteTimezone(): void
    {
        $tz = date_default_timezone_get();
        date_default_timezone_set('America/St_Johns');
        $d = new DateTime('2011-03-31 11:59:59');
        $f = new YearField();
        $f->increment($d);
        $this->assertSame('2012-01-01 00:00:00', $d->format('Y-m-d H:i:s'));

        $d = new DateTime('2011-03-15 11:15:00');
        $f->increment($d, true);
        $this->assertSame('2010-12-31 23:59:00', $d->format('Y-m-d H:i:s'));
        date_default_timezone_set($tz);
    }

    /**
     * @covers \Cron\MonthField::increment
     */
    public function testIncrementsYearAsNeeded(): void
    {
        $f = new YearField();
        $d = new DateTime('2011-12-15 00:00:00');
        $f->increment($d);
        $this->assertSame('2012-01-01 00:00:00', $d->format('Y-m-d H:i:s'));
    }

    /**
     * @covers \Cron\MonthField::increment
     */
    public function testDecrementsYearAsNeeded(): void
    {
        $f = new YearField();
        $d = new DateTime('2011-01-15 00:00:00');
        $f->increment($d, true);
        $this->assertSame('2010-12-31 23:59:00', $d->format('Y-m-d H:i:s'));
    }
}
