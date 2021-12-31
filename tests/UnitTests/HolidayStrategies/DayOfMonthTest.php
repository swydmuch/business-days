<?php
declare(strict_types=1);

namespace BusinessDays\Tests;

use BusinessDays\Exceptions\WrongDayForMonthException;
use BusinessDays\Exceptions\WrongMonthException;
use BusinessDays\Holidays\DayOfMonth;
use PHPUnit\Framework\TestCase;

class DayOfMonthTest extends TestCase
{
    /**
     * @dataProvider properDayNumberProvider
     */
    public function testCreating_ProperNumberOfDay_ValueIsSet(int $expectedDay):void
    {
        //act
        $holiday = new DayOfMonth($expectedDay, 1);

        //assert
        $this->assertSame($expectedDay, $holiday->dayNumber());
    }

    /**
     * @dataProvider properMonthNumberProvider
     */
    public function testCreating_ProperNumberOfMonth_ValueIsSet(int $expectedMonth): void
    {
        //act
        $holiday = new DayOfMonth(1, $expectedMonth);

        //assert
        $this->assertSame($expectedMonth, $holiday->monthNumber());
    }

    /**
     * @dataProvider wrongDayNumberProvider
     */
    public function testCreating_WrongNumberOfDay_ExceptionIsThrown(int $day): void
    {
        //assert
        $this->expectException(WrongDayForMonthException::class);

        //act
        new DayOfMonth($day, 1);
    }

    /**
     * @dataProvider wrongMonthNumberProvider
     */
    public function testCreating_WrongNumberOfMonth_ExceptionIsThrown(int $month): void
    {
        //assert
        $this->expectException(WrongMonthException::class);

        //act
        new DayOfMonth(1, $month);
    }

    /**
     * @return int[][]
     */
    public function wrongDayNumberProvider(): array
    {
        return [
            [-1],
            [0],
            [32]
        ];
    }

    /**
     * @return int[][]
     */
    public function properDayNumberProvider(): array
    {
        return [
            [1],
            [31]
        ];
    }

    /**
     * @return int[][]
     */
    public function properMonthNumberProvider(): array
    {
        return [
            [1],
            [12]
        ];
    }

    /**
     * @return int[][]
     */
    public function wrongMonthNumberProvider(): array
    {
        return [
            [-1],
            [0],
            [13]
        ];
    }
}