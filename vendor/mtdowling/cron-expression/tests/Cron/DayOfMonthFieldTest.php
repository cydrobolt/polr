<?php

namespace Cron\Tests;

use Cron\DayOfMonthField;

use DateTime;

/**
 * @author Michael Dowling <mtdowling@gmail.com>
 */
class DayOfMonthFieldTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Cron\DayOfMonthField::validate
     */
    public function testValdatesField()
    {
        $f = new DayOfMonthField();
        $this->assertTrue($f->validate('1'));
        $this->assertTrue($f->validate('*'));
        $this->assertTrue($f->validate('*/3,1,1-12'));
        $this->assertTrue($f->validate('5W,L'));
        $this->assertFalse($f->validate('1.'));
    }

    /**
     * @covers Cron\DayOfMonthField::isSatisfiedBy
     */
    public function testChecksIfSatisfied()
    {
        $f = new DayOfMonthField();
        $this->assertTrue($f->isSatisfiedBy(new DateTime(), '?'));
    }

    /**
     * @covers Cron\DayOfMonthField::increment
     */
    public function testIncrementsDate()
    {
        $d = new DateTime('2011-03-15 11:15:00');
        $f = new DayOfMonthField();
        $f->increment($d);
        $this->assertEquals('2011-03-16 00:00:00', $d->format('Y-m-d H:i:s'));

        $d = new DateTime('2011-03-15 11:15:00');
        $f->increment($d, true);
        $this->assertEquals('2011-03-14 23:59:00', $d->format('Y-m-d H:i:s'));
    }
}
