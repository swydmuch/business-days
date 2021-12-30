<?php
declare(strict_types=1);

namespace BusinessDays\Tests;

use BusinessDays\Exceptions\DifferentRegionDaysInRangeException;
use BusinessDays\Exceptions\DuplicatedDatesForDatesCollection;
use BusinessDays\Exceptions\EmptyInputForDatesCollection;
use BusinessDays\Exceptions\EndingDayIsNotBusinessDayException;
use BusinessDays\Exceptions\StartingDayIsGreaterThanEndingException;
use BusinessDays\Exceptions\StartingDayIsNotBusinessDayException;
use BusinessDays\Exceptions\WeekendDateException;
use BusinessDays\Exceptions\WeekendDateForDatesCollection;
use BusinessDays\Holidays\Strategy;
use BusinessDays\Support\MotherObjects\Day;
use PHPUnit\Framework\TestCase;
use BusinessDays\Range;

class RangeTest extends TestCase
{
    public function testCreatingRange_StartGreaterThanEnd_ExceptionIsThrown(): void
    {
        //arrange
        $startDate = Day::createFromStringWithSpecificStrategy('2019-10-22', $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy('2019-10-21', $this->createAlwaysNotHolidayStrategy());

        //assert
        $this->expectException(StartingDayIsGreaterThanEndingException::class);

        //act
        new Range($startDate, $endDate);
    }

    public function testCountingBusinessDays_SameDateForStartAndEnd_ReturnOne(): void
    {
        //arrange
        $startDate = Day::createFromStringWithSpecificStrategy('2019-10-21', $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy('2019-10-21', $this->createAlwaysNotHolidayStrategy());
        $expectedNumber = 1;
        $range = new Range($startDate, $endDate);

        //act
        $countedNumber = $range->countBusinessDays();

        //assert
        $this->assertSame($expectedNumber, $countedNumber);
    }

    public function testCountingBusinessDays_EndDateNextToStart_ReturnTwo(): void
    {
        //arrange
        $startDate = Day::createFromStringWithSpecificStrategy('2019-10-21', $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy('2019-10-22', $this->createAlwaysNotHolidayStrategy());
        $expectedNumber = 2;
        $range = new Range($startDate, $endDate);

        //act
        $countedNumber = $range->countBusinessDays();

        //assert
        $this->assertSame($expectedNumber, $countedNumber);
    }

    public function testCountingBusinessDays_EndDateTwoDaysAfterStart_ReturnThree(): void
    {
        //arrange
        $startDate = Day::createFromStringWithSpecificStrategy('2019-10-21', $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy('2019-10-23', $this->createAlwaysNotHolidayStrategy());
        $expectedNumber = 3;
        $range = new Range($startDate, $endDate);

        //act
        $countedNumber = $range->countBusinessDays();

        //assert
        $this->assertSame($expectedNumber, $countedNumber);
    }

    public function testGettingDays_SameDateForStartAndEnd_ReturnStartDate(): void
    {
        //arrange
        $dateString = '2019-10-21';
        $date = Day::createFromStringWithSpecificStrategy($dateString, $this->createAlwaysNotHolidayStrategy());
        $range = new Range($date, $date);
        $expectedDays = [
            $date
        ];

        //act
        $calculatedDays = $range->businessDays();

        //assert
        $this->assertEquals($expectedDays, $calculatedDays);
    }

    public function testGettingDays_EndDateNextToStart_ReturnStartAndEndDates(): void
    {
        //arrange
        $startDateString = '2019-10-21';
        $endDateString = '2019-10-22';
        $startDate = Day::createFromStringWithSpecificStrategy($startDateString, $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy($endDateString, $this->createAlwaysNotHolidayStrategy());
        $range = new Range($startDate, $endDate);
        $expectedDays = [
            $startDate,
            $endDate,
        ];

        //act
        $calculatedDays = $range->businessDays();

        //assert
        $this->assertEquals($expectedDays, $calculatedDays);
    }

    public function testGettingDays_EndDateTwoDaysAfterStart_ReturnThreeDates(): void
    {
        //arrange
        $startDateString = '2019-10-21';
        $middleDateString = '2019-10-22';
        $endDateString = '2019-10-23';
        $startDate = Day::createFromStringWithSpecificStrategy($startDateString, $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy($endDateString, $this->createAlwaysNotHolidayStrategy());
        $range = new Range($startDate, $endDate);
        $expectedDays = [
            $startDate,
            Day::createFromStringWithSpecificStrategy($middleDateString, $this->createAlwaysNotHolidayStrategy()),
            $endDate,
        ];

        //act
        $calculatedDays = $range->businessDays();

        //assert
        $this->assertEquals($expectedDays, $calculatedDays);
    }

    public function testCreating_StartDateIsDayOff_ThrownException(): void
    {
        //assert
        $this->expectException(StartingDayIsNotBusinessDayException::class);

        //arrange
        $startDate = Day::createFromStringWithSpecificStrategy('2019-10-20', $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy('2019-10-21', $this->createAlwaysNotHolidayStrategy());

        //act
        new Range($startDate, $endDate);
    }

    public function testCreating_EndDateIsDayOff_ThrownException(): void
    {
        //arrange
        $this->expectException(EndingDayIsNotBusinessDayException::class);

        //arrange
        $startDate = Day::createFromStringWithSpecificStrategy('2019-10-25', $this->createAlwaysNotHolidayStrategy());
        $endDate = Day::createFromStringWithSpecificStrategy('2019-10-26', $this->createAlwaysNotHolidayStrategy());

        //act
        new Range($startDate, $endDate);
    }

    public function testCreating_DayFromDifferentRegion_ThrownException(): void
    {
        //arrange

        $startDate = $this->createMock(\BusinessDays\Day::class);
        $startDate->method('sameHolidayStrategy')->willReturn(false);
        $endDate = $this->createMock(\BusinessDays\Day::class);
        $endDate->method('sameHolidayStrategy')->willReturn(false);

        //assert
        $this->expectException(DifferentRegionDaysInRangeException::class);

        //act
        new Range($startDate, $endDate);
    }


    public function testCreationRangeFromString_TwoSameBusinessDay_ProperClassInstance(): void
    {
        //act
        $range = Range::createFromString(
            Day::DEFAULT_DATE,
            Day::DEFAULT_DATE,
            Day::DEFAULT_REGION_CODE
        );

        //assert
        $this->assertInstanceOf(Range::class, $range);
    }


    public function createAlwaysNotHolidayStrategy(): Strategy
    {
        $holidayStrategy = $this->createMock(Strategy::class);
        $holidayStrategy->method('same')->willReturn(true);
        $holidayStrategy->method('isPermanentHoliday')->willReturn(false);
        $holidayStrategy->method('isMovableHoliday')->willReturn(false);
        return $holidayStrategy;
    }


}

